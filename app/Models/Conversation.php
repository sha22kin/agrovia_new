<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    /********
     * Column:
     * id              =bigint(20):None,
     * buyer_id        =bigint(20):None,
     * seller_id       =bigint(20):None,
     * last_message_at =timestamp:null
     * created_at      =timestamp:null
     * updated_at      =timestamp:null
     ****/

    protected $casts = ['last_message_at' => 'datetime'];

    public function buyer(){
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller(){
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function messages(){
        return $this->hasMany(Message::class);
    }

    public function lastMessage(){
        return $this->hasOne(Message::class)->latestOfMany();
    }
}
