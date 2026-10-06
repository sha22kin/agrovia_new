<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    /********
     * Column:
     * id         =bigint(20):None,
     * user_id    =bigint(20):None the buyer,
     * post_id    =bigint(20):None the saved ad (posts.type 3),
     * created_at =timestamp:null
     * updated_at =timestamp:null
     ****/

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function post(){
        return $this->belongsTo(Post::class);
    }

    // Whether the user saved the ad.
    public static function has($userId, $postId): bool
    {
        return static::where('user_id', $userId)->where('post_id', $postId)->exists();
    }
}
