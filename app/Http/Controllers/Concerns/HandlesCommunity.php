<?php

namespace App\Http\Controllers\Concerns;

use Auth;
use App\Models\CommunityComment;
use App\Models\CommunityLike;
use App\Models\CommunityPost;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

// Community discussions shared by the buyer and seller dashboards. $role is 'buyer' or 'seller',
// $view is the community view of the theme (it shows the list, the form or one post).
trait HandlesCommunity
{
    protected function communityList(Request $r, $role, $view){

        $user =Auth::user();

        $tag =in_array($r->tag,CommunityPost::TAGS)?$r->tag:null;

        $posts =CommunityPost::with('user.imageFile')->whereHas('user',fn ($q) => $q->notDeactivated())
            ->withCount(['likes','comments' => fn ($q) => $q->whereHas('user',fn ($u) => $u->notDeactivated()),'likes as liked' => fn ($q) => $q->where('user_id',$user->id)])
            ->when($tag,fn ($q) => $q->where('tag',$tag))->latest()->latest('id')->paginate(10)->withQueryString();

        return view($view,['role'=>$role,'page'=>'list','title'=>'Community','posts'=>$posts,'tag'=>$tag]);
    }

    protected function communityHandle(Request $r, $role, $view, $action, $id=null){

        $user =Auth::user();

        if($action=='create'){

            if($r->isMethod('post')){
                $r->validate([
                    'tag' => ['required',Rule::in(CommunityPost::tagsFor($user))],
                    'title' => 'required|max:191',
                    'body' => 'required|max:3000',
                ]);

                $post =new CommunityPost();
                $post->user_id =$user->id;
                $post->tag =$r->tag;
                $post->title =$r->title;
                $post->body =$r->body;
                $post->save();

                Session()->flash('success','Your post is published');
                return redirect()->route($role.'.communityAction',['view',$post->id]);
            }

            return view($view,['role'=>$role,'page'=>'form','title'=>'New Post']);
        }

        $post =CommunityPost::with('user.imageFile')->whereHas('user',fn ($q) => $q->notDeactivated())
            ->withCount(['likes','comments' => fn ($q) => $q->whereHas('user',fn ($u) => $u->notDeactivated()),'likes as liked' => fn ($q) => $q->where('user_id',$user->id)])->find($id);
        if(!$post){
            Session()->flash('error','This post is not found');
            return redirect()->route($role.'.community');
        }

        if($action=='like' && $r->isMethod('post')){
            $like =CommunityLike::where('post_id',$post->id)->where('user_id',$user->id)->first();
            if($like){
                $like->delete();
            }else{
                $like =new CommunityLike();
                $like->post_id =$post->id;
                $like->user_id =$user->id;
                $like->save();
            }
            return back();
        }

        if($action=='comment' && $r->isMethod('post')){
            $r->validate(['body' => 'required|max:1000']);

            $comment =new CommunityComment();
            $comment->post_id =$post->id;
            $comment->user_id =$user->id;
            $comment->body =trim($r->body);
            $comment->save();

            Session()->flash('success','Your comment is posted');
            return redirect()->to(route($role.'.communityAction',['view',$post->id]).'#comments');
        }

        // Authors remove their own posts and comments only.
        if($action=='delete-comment' && $r->isMethod('post')){
            $post->comments()->where('user_id',$user->id)->where('id',$r->comment)->delete();

            Session()->flash('success','Your comment is deleted');
            return redirect()->to(route($role.'.communityAction',['view',$post->id]).'#comments');
        }

        if($action=='delete' && $r->isMethod('post')){
            if($post->user_id!=$user->id){
                Session()->flash('error','You can delete your own posts only');
                return redirect()->route($role.'.communityAction',['view',$post->id]);
            }
            $post->comments()->delete();
            $post->likes()->delete();
            $post->delete();

            Session()->flash('success','Your post is deleted');
            return redirect()->route($role.'.community');
        }

        $comments =$post->comments()->whereHas('user',fn ($q) => $q->notDeactivated())->with('user.imageFile')->oldest()->get();

        return view($view,['role'=>$role,'page'=>'view','title'=>$post->title,'post'=>$post,'comments'=>$comments]);
    }
}
