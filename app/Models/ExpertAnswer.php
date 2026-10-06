<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpertAnswer extends Model
{
    /********
     * Column:
     * id          =bigint(20):None,
     * question_id =bigint(20):None,
     * expert_id   =bigint(20):None,
     * body        =text:None,
     * created_at  =timestamp:null
     * updated_at  =timestamp:null
     ****/

    public function question(){
        return $this->belongsTo(ExpertQuestion::class, 'question_id');
    }

    public function expert(){
        return $this->belongsTo(User::class, 'expert_id');
    }
}
