<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /********
     * Column:
     * id              =bigint(20):None,
     * conversation_id =bigint(20):None,
     * sender_id       =bigint(20):None,
     * body            =text:None,
     * read_at         =timestamp:null when the other side opened it
     * created_at      =timestamp:null
     * updated_at      =timestamp:null
     ****/

    public function conversation(){
        return $this->belongsTo(Conversation::class);
    }

    public function sender(){
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Messages sent to the user that were not opened yet.
    public static function unreadCount($user): int
    {
        return static::whereNull('read_at')->where('sender_id', '<>', $user->id)
            ->whereIn('conversation_id', Conversation::where(function ($q) use ($user) {
                $q->where('buyer_id', $user->id)->orWhere('seller_id', $user->id);
            })->select('id'))->count();
    }
}
