<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ExpertAvailability extends Model
{
    /********
     * Column:
     * id          =bigint(20):None,
     * expert_id   =bigint(20):None,
     * day_of_week =tinyint:None 0 = Sunday ... 6 = Saturday,
     * start_time  =time:None,
     * end_time    =time:None,
     * created_at  =timestamp:null
     * updated_at  =timestamp:null
     ****/

    public const DAYS = [0 => 'Sunday', 1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday'];

    // Slots per day, and the shortest slot in minutes.
    public const MAX_PER_DAY = 6;
    public const MIN_MINUTES = 15;

    public function expert(){
        return $this->belongsTo(User::class, 'expert_id');
    }

    // "10:00–12:00 PM" when both times are in the same half of the day, else "10:00 AM–1:00 PM".
    public function label(): string
    {
        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);

        return $start->format('A') == $end->format('A')
            ? $start->format('g:i').'–'.$end->format('g:i A')
            : $start->format('g:i A').'–'.$end->format('g:i A');
    }
}
