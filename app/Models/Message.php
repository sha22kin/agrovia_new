<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /********
     * Column:
     * id                 =bigint(20):None,
     * conversation_id    =bigint(20):None,
     * sender_id          =bigint(20):None,
     * post_id            =bigint(20):null the ad the message is about, shown quoted above it
     * reply_to_id        =bigint(20):null the message this one answers, shown quoted above it
     * body               =text:None, emptied when the message is deleted for everyone
     * read_at            =timestamp:null when the other side opened it
     * pinned_at          =timestamp:null
     * deleted_at         =timestamp:null deleted for everyone by the sender
     * sender_hidden_at   =timestamp:null deleted for me by the sender
     * receiver_hidden_at =timestamp:null deleted for me by the receiver
     * created_at         =timestamp:null
     * updated_at         =timestamp:null
     ****/

    // After the other side saw a message, the sender may still delete it for everyone for this many minutes.
    public const DELETE_FOR_EVERYONE_MINUTES = 2;

    protected $casts = [
        'read_at' => 'datetime',
        'pinned_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function conversation(){
        return $this->belongsTo(Conversation::class);
    }

    public function sender(){
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function post(){
        return $this->belongsTo(Post::class);
    }

    public function replyTo(){
        return $this->belongsTo(Message::class, 'reply_to_id');
    }

    public function likes(){
        return $this->hasMany(MessageLike::class);
    }

    // Messages the user did not delete for themself.
    public function scopeVisibleTo($query, $userId){
        return $query->where(function ($q) use ($userId) {
            $q->where(fn ($m) => $m->where('sender_id', $userId)->whereNull('sender_hidden_at'))
              ->orWhere(fn ($m) => $m->where('sender_id', '<>', $userId)->whereNull('receiver_hidden_at'));
        });
    }

    public function isDeleted(): bool
    {
        return $this->deleted_at !== null;
    }

    // The sender can take a message back until the other side has seen it for DELETE_FOR_EVERYONE_MINUTES.
    public function canDeleteForEveryone($userId): bool
    {
        return $this->sender_id == $userId && !$this->isDeleted()
            && (!$this->read_at || $this->read_at->gt(now()->subMinutes(self::DELETE_FOR_EVERYONE_MINUTES)));
    }

    public function likedBy($userId): bool
    {
        return $this->likes->contains('user_id', $userId);
    }

    // Short text of the message for quotes and the inbox list.
    public function preview($length = 60): string
    {
        return $this->isDeleted() ? 'This message was deleted' : \Illuminate\Support\Str::limit(strip_tags($this->body), $length);
    }

    // Messages sent to the user that were not opened yet.
    public static function unreadCount($user): int
    {
        return static::whereNull('read_at')->whereNull('deleted_at')->where('sender_id', '<>', $user->id)
            ->whereIn('conversation_id', Conversation::where(function ($q) use ($user) {
                $q->where('buyer_id', $user->id)->orWhere('seller_id', $user->id);
            })->select('id'))->count();
    }
}
