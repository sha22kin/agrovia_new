<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityPost extends Model
{
    /********
     * Column:
     * id         =bigint(20):None,
     * user_id    =bigint(20):None the author,
     * tag        =varchar(30):None one of TAGS,
     * title      =varchar(191):None,
     * body       =text:None,
     * created_at =timestamp:null
     * updated_at =timestamp:null
     ****/

    public const EXPERT_TAG = 'Expert Advice';

    public const TAGS = ['Market Price', 'Farming Tips', 'Discussion', 'Question', self::EXPERT_TAG];

    // The topics a user can choose for a new post. "Expert Advice" is for experts only (and is their first choice).
    public static function tagsFor($user): array
    {
        $tags = array_values(array_diff(self::TAGS, [self::EXPERT_TAG]));

        return $user && $user->isExpert() ? array_merge([self::EXPERT_TAG], $tags) : $tags;
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function comments(){
        return $this->hasMany(CommunityComment::class, 'post_id');
    }

    public function likes(){
        return $this->hasMany(CommunityLike::class, 'post_id');
    }
}
