<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpertProfile extends Model
{
    /********
     * Column:
     * id              =bigint(20):None,
     * user_id         =bigint(20):None unique,
     * headline        =varchar(150):null e.g. "Agronomist · Soil & Crop Health Specialist",
     * specializations =varchar(300):null comma separated,
     * fee             =decimal(12,2):null consultation fee,
     * session_minutes =smallint:30 length of one consultation (15, 30 or 60),
     * verified_at     =timestamp:null set by the admin
     * created_at      =timestamp:null
     * updated_at      =timestamp:null
     ****/

    // Lengths of a consultation an expert can offer.
    public const SESSION_MINUTES = [15, 30, 60];

    protected $casts = ['verified_at' => 'datetime'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function isVerified(): bool
    {
        return (bool) $this->verified_at;
    }

    public function specializationList(): array
    {
        return array_values(array_filter(array_map('trim', explode(',', (string) $this->specializations))));
    }

    // The profile of the user, or an unsaved empty one.
    public static function forUser($userId): self
    {
        $profile = static::where('user_id', $userId)->first();
        if (!$profile) {
            $profile = new static();
            $profile->user_id = $userId;
        }

        return $profile;
    }
}
