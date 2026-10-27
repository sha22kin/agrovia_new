<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageLike extends Model
{
    /********
     * Column:
     * id         =bigint(20):None,
     * message_id =bigint(20):None,
     * user_id    =bigint(20):None, unique with message_id
     * created_at =timestamp:null
     * updated_at =timestamp:null
     ****/

    protected $fillable = ['message_id', 'user_id'];

    public function message(){
        return $this->belongsTo(Message::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
