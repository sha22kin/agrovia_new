<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpertQuestion extends Model
{
    /********
     * Column:
     * id         =bigint(20):None,
     * user_id    =bigint(20):None the asker,
     * title      =varchar(191):None,
     * body       =text:null,
     * topic      =varchar(60):null e.g. Crop Disease, Irrigation, Soil Health,
     * status     =varchar(10):open open, closed,
     * created_at =timestamp:null
     * updated_at =timestamp:null
     ****/

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function answers(){
        return $this->hasMany(ExpertAnswer::class, 'question_id');
    }

    // Open questions this expert has not answered yet.
    public function scopeToAnswer($query, $expertId){
        return $query->where('status', 'open')->where('user_id', '<>', $expertId)
            ->whereDoesntHave('answers', fn ($q) => $q->where('expert_id', $expertId));
    }

    // The questions whose topic is one of the given specializations come first (case does not matter).
    public function scopeSpecializedFirst($query, array $specializations){
        if (!$specializations) {
            return $query;
        }

        $marks = implode(',', array_fill(0, count($specializations), '?'));

        return $query->orderByRaw('CASE WHEN LOWER(topic) IN ('.$marks.') THEN 0 ELSE 1 END', array_map('mb_strtolower', $specializations));
    }

    // Font Awesome icon picked from what the question is about.
    public function icon(): string
    {
        $text = mb_strtolower($this->title);

        return match (true) {
            preg_match('/disease|spot|pest|insect|blight|blast|fung|virus/', $text) === 1 => 'fa-bug',
            preg_match('/irrigat|water|drought|rain|flood/', $text) === 1 => 'fa-droplet',
            preg_match('/soil|fertili|urea|manure|compost|ph\b/', $text) === 1 => 'fa-seedling',
            preg_match('/cow|cattle|goat|poultry|chicken|fish|feed/', $text) === 1 => 'fa-paw',
            default => 'fa-circle-question',
        };
    }
}
