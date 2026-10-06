<?php

namespace App\Http\Controllers\Concerns;

use Auth;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

// Inbox and chat shared by the buyer and seller dashboards. $role is 'buyer' or 'seller';
// only a buyer can start a chat (from an ad), a seller answers the chats that reach them.
trait HandlesMessages
{
    protected function messagesPage(Request $r, $role, $view){

        $user =Auth::user();
        // A buyer starts the chat; a seller or an expert is the one who answers.
        $mine =$role=='buyer'?'buyer_id':'seller_id';
        $peerRelation =$role=='buyer'?'seller':'buyer';

        $selected =$r->c?Conversation::where($mine,$user->id)->find($r->c):null;

        // A buyer coming from an ad: open the chat with that seller, or a blank one when there is none yet.
        $draft =null;
        if(!$selected && $role=='buyer' && $r->to){
            $seller =User::find($r->to);
            if($seller && $seller->id!=$user->id && ($seller->isSeller() || $seller->isExpert())){
                $existing =Conversation::where($mine,$user->id)->where('seller_id',$seller->id)->first();
                if($existing){
                    return redirect()->route($role.'.messages',['c'=>$existing->id]);
                }
                $draft =$seller;
            }
        }

        if(!$selected && !$draft){
            $selected =Conversation::where($mine,$user->id)->orderByDesc('last_message_at')->first();
        }

        // Opening a chat reads what the other side sent.
        if($selected){
            Message::where('conversation_id',$selected->id)->where('sender_id','<>',$user->id)->whereNull('read_at')->update(['read_at'=>now()]);
        }

        $conversations =Conversation::where($mine,$user->id)
            ->with([$peerRelation.'.imageFile','lastMessage'])
            ->withCount(['messages as unread_count' => fn ($q) => $q->where('sender_id','<>',$user->id)->whereNull('read_at')])
            ->orderByDesc('last_message_at')->get();

        $peer =$selected?$selected->{$peerRelation}:$draft;
        $thread =$selected?$selected->messages()->latest('id')->take(200)->get()->reverse()->values():collect();

        return view($view,compact('role','conversations','selected','draft','peer','thread'));
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
            Session()->flash('error','This chat is not found');
            return redirect()->route($role.'.messages');
        }

        $message =new Message();
        $message->sender_id =$user->id;
        $message->body =trim($r->body);

        $conversation->last_message_at =now();
        $conversation->save();
        $conversation->messages()->save($message);

        return redirect()->route($role.'.messages',['c'=>$conversation->id]);
    }
}
