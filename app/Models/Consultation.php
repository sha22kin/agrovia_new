<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    /********
     * Column:
     * id            =bigint(20):None,
     * expert_id     =bigint(20):None,
     * user_id       =bigint(20):None the farmer,
     * topic         =varchar(191):None,
     * mode          =varchar(10):video video, phone, onsite,
     * scheduled_at  =timestamp:null,
     * status        =varchar(10):pending pending, confirmed, completed, cancelled,
     * fee           =decimal(12,2):0,
     * meeting_link  =varchar(255):null,
     * rating        =tinyint:null 1-5,
     * review        =text:null,
     * created_at    =timestamp:null
     * updated_at    =timestamp:null
     ****/

    public const MODES = ['video' => 'Video Call', 'phone' => 'Phone Call', 'onsite' => 'On-site Visit'];

    protected $casts = ['scheduled_at' => 'datetime'];

    public function expert(){
        return $this->belongsTo(User::class, 'expert_id');
    }

    public function farmer(){
        return $this->belongsTo(User::class, 'user_id');
    }

    // Confirmed sessions that have not started yet.
    public function scopeUpcoming($query){
        return $query->where('status', 'confirmed')->where('scheduled_at', '>=', now());
    }

    public function modeLabel(): string
    {
        return self::MODES[$this->mode] ?? ucfirst($this->mode);
    }

    // "Today, 4:00 PM", "Tomorrow, 11:00 AM", "Aug 31, 10:00 AM"
    public function whenLabel(): string
    {
        if (!$this->scheduled_at) {
            return 'Time not set';
        }

        $day = match (true) {
            $this->scheduled_at->isToday() => 'Today',
            $this->scheduled_at->isTomorrow() => 'Tomorrow',
            default => $this->scheduled_at->format('M d'),
        };

        return $day.', '.$this->scheduled_at->format('g:i A');
    }
}
