<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityComment extends Model
{
    /********
     * Column:
     * id         =bigint(20):None,
     * post_id    =bigint(20):None,
     * user_id    =bigint(20):None the commenter,
     * body       =text:None,
     * created_at =timestamp:null
     * updated_at =timestamp:null
     ****/

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function post(){
        return $this->belongsTo(CommunityPost::class, 'post_id');
    }
}
