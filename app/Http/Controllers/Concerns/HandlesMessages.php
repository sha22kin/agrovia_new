<?php

namespace App\Http\Controllers\Concerns;

use Auth;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\MessageLike;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

// Inbox and chat shared by the buyer and seller dashboards. $role is 'buyer' or 'seller';
// only a buyer can start a chat (from an ad), a seller answers the chats that reach them.
trait HandlesMessages
{
    protected function messagesPage(Request $r, $role, $view){

        $user =Auth::user();
        $me =$user->id;
        // A buyer starts the chat; a seller or an expert is the one who answers.
        $mine =$role=='buyer'?'buyer_id':'seller_id';
        $peerRelation =$role=='buyer'?'seller':'buyer';

        $selected =$r->c?Conversation::where($mine,$me)->find($r->c):null;

        // A buyer coming from an ad: open the chat with that seller, or a blank one when there is none yet.
        $draft =null;
        if(!$selected && $role=='buyer' && $r->to){
            $seller =User::find($r->to);
            if($seller && $seller->id!=$me && ($seller->isSeller() || $seller->isExpert())){
                $existing =Conversation::where($mine,$me)->where('seller_id',$seller->id)->first();
                if($existing){
                    return redirect()->route($role.'.messages',array_filter(['c'=>$existing->id,'ad'=>$r->ad]));
                }
                $draft =$seller;
            }
        }

        if(!$selected && !$draft){
            $selected =Conversation::where($mine,$me)->orderByDesc('last_message_at')->first();
        }

        // Opening a chat reads what the other side sent.
        if($selected){
            $this->markRead($selected,$me);
        }

        $conversations =$this->inboxList($user,$mine,$peerRelation);

        $peer =$selected?$selected->{$peerRelation}:$draft;
        $thread =$selected?$this->threadOf($selected,$me):collect();
        $version =$selected?$this->threadVersion($selected,$me):'';

        // A buyer coming from an ad writes about it: the ad is shown above the message box and sent with the message.
        $aboutAd =$role=='buyer' && $peer ? $this->sellerAd($peer->id,$r->ad) : null;

        return view($view,compact('role','conversations','selected','draft','peer','thread','version','aboutAd'));
    }

    // The user's chats, newest first, with how many messages they did not read.
    protected function inboxList($user, $mine, $peerRelation){
        return Conversation::where($mine,$user->id)
            ->with([$peerRelation.'.imageFile','lastMessage'])
            ->withCount(['messages as unread_count' => fn ($q) => $q->where('sender_id','<>',$user->id)->whereNull('read_at')->whereNull('deleted_at')])
            ->orderByDesc('last_message_at')->get();
    }

    protected function markRead($conversation, $me){
        Message::where('conversation_id',$conversation->id)->where('sender_id','<>',$me)->whereNull('read_at')->update(['read_at'=>now()]);
    }

    // The last 200 messages of the chat the user did not delete for themself, oldest first.
    protected function threadOf($conversation, $me){
        return $conversation->messages()->visibleTo($me)
            ->with(['sender:id,name','post.galleryFiles','replyTo.sender:id,name','likes'])
            ->latest('id')->take(200)->get()->reverse()->values();
    }

    // Changes whenever something in the thread changes for the user: a new, read, liked, pinned or deleted message.
    // Built from the state itself, not from updated_at, which has whole seconds: two changes in one second
    // (a like, then a pin) would otherwise look like one and the second never reach the other side.
    protected function threadVersion($conversation, $me){
        $messages =$conversation->messages()->visibleTo($me)->latest('id')->take(200)
            ->get(['id','read_at','pinned_at','deleted_at'])
            ->map(fn ($m) => [$m->id,(bool) $m->read_at,(string) $m->pinned_at,(bool) $m->deleted_at]);
        $likes =MessageLike::whereIn('message_id',$messages->pluck(0))->orderBy('id')->get(['message_id','user_id'])
            ->map(fn ($l) => $l->message_id.':'.$l->user_id);
        return md5(json_encode([$messages,$likes]));
    }

    protected function threadHtml($conversation, $me){
        $thread =$this->threadOf($conversation,$me);
        return view('dashboard.partials.message-thread',compact('thread','me'))->render();
    }

    // Called every few seconds by an open inbox: the open chat when it changed since version $r->v
    // (what the other side sent counts as read now) and the refreshed conversation list.
    protected function messagesPoll(Request $r, $role){

        $user =Auth::user();
        $me =$user->id;
        $mine =$role=='buyer'?'buyer_id':'seller_id';
        $peerRelation =$role=='buyer'?'seller':'buyer';

        $selected =$r->c?Conversation::where($mine,$me)->find($r->c):null;

        $data =[];
        if($selected){
            $this->markRead($selected,$me);
            $data['v'] =$this->threadVersion($selected,$me);
            if($data['v']!==$r->v){
                $data['thread'] =$this->threadHtml($selected,$me);
            }
        }

        $conversations =$this->inboxList($user,$mine,$peerRelation);
        $data['list'] =view('dashboard.partials.message-list',compact('role','conversations','selected','me'))->render();

        return response()->json($data);
    }

    // A live ad of the seller, or null.
    protected function sellerAd($sellerId, $adId){
        if(!$adId || !is_numeric($adId)){
            return null;
        }
        return Post::live()->where('addedby_id',$sellerId)->with('galleryFiles')->find((int) $adId);
    }

    protected function messagesSend(Request $r, $role){

        $user =Auth::user();
        $mine =$role=='buyer'?'buyer_id':'seller_id';

        $r->validate(['body' => 'required|string|max:2000']);

        $conversation =null;
        if($r->conversation){
            $conversation =Conversation::where($mine,$user->id)->find($r->conversation);
        }elseif($role=='buyer' && $r->to){
            $seller =User::find($r->to);
            if($seller && $seller->id!=$user->id && ($seller->isSeller() || $seller->isExpert())){
                $conversation =Conversation::where('buyer_id',$user->id)->where('seller_id',$seller->id)->first();
                if(!$conversation){
                    $conversation =new Conversation();
                    $conversation->buyer_id =$user->id;
                    $conversation->seller_id =$seller->id;
                }
            }
        }

        if(!$conversation){
            if($r->expectsJson()){
                return response()->json(['message'=>'This chat is not found'],404);
            }
            Session()->flash('error','This chat is not found');
            return redirect()->route($role.'.messages');
        }

        $message =new Message();
        $message->sender_id =$user->id;
        if($role=='buyer' && ($ad =$this->sellerAd($conversation->seller_id,$r->ad))){
            $message->post_id =$ad->id;
        }
        // A reply quotes a message of the same chat that is still there.
        if($conversation->exists && $r->reply_to){
            $message->reply_to_id =$conversation->messages()->visibleTo($user->id)->whereNull('deleted_at')->whereKey((int) $r->reply_to)->value('id');
        }
        $message->body =trim($r->body);

        $conversation->last_message_at =now();
        $conversation->save();
        $conversation->messages()->save($message);

        $url =route($role.'.messages',['c'=>$conversation->id]);

        // The inbox sends with fetch and shows the returned thread itself.
        if($r->expectsJson()){
            return response()->json([
                'thread' => $this->threadHtml($conversation,$user->id),
                'v'      => $this->threadVersion($conversation,$user->id),
                'url'    => $url,
            ]);
        }

        return redirect($url);
    }

    // like, pin, delete_me or delete_all on a message of one of the user's chats.
    protected function messagesAction(Request $r, $role){

        $user =Auth::user();
        $me =$user->id;
        $mine =$role=='buyer'?'buyer_id':'seller_id';

        $r->validate(['message' => 'required|integer', 'action' => 'required|in:like,pin,delete_me,delete_all']);

        $message =Message::whereHas('conversation',fn ($q) => $q->where($mine,$me))->visibleTo($me)->find($r->message);
        if(!$message){
            return response()->json(['message'=>'This message is not found'],404);
        }
        if($message->isDeleted() && $r->action!='delete_me'){
            return response()->json(['message'=>'This message was deleted'],422);
        }

        if($r->action=='like'){
            $like =MessageLike::where('message_id',$message->id)->where('user_id',$me)->first();
            if($like){
                $like->delete();
            }else{
                MessageLike::create(['message_id'=>$message->id,'user_id'=>$me]);
            }
            $message->touch();
        }elseif($r->action=='pin'){
            $message->pinned_at =$message->pinned_at?null:now();
            $message->save();
        }elseif($r->action=='delete_me'){
            $message->{$message->sender_id==$me?'sender_hidden_at':'receiver_hidden_at'} =now();
            $message->save();
        }else{
            if(!$message->canDeleteForEveryone($me)){
                return response()->json(['message'=>'This message can only be deleted for you now: it was seen more than '.Message::DELETE_FOR_EVERYONE_MINUTES.' minutes ago.'],422);
            }
            // The text and the quoted ad go; the other side sees "This message was deleted".
            $message->deleted_at =now();
            $message->body ='';
            $message->post_id =null;
            $message->pinned_at =null;
            $message->save();
            $message->likes()->delete();
        }

        return response()->json([
            'thread' => $this->threadHtml($message->conversation,$me),
            'v'      => $this->threadVersion($message->conversation,$me),
        ]);
    }
}
