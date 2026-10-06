<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessDocument extends Model
{
    /********
     * Column:
     * id            =bigint(20):None,
     * user_id       =bigint(20):None,
     * type          =varchar(40):None one of TYPES,
     * file_path     =varchar(255):None path on the local (private) disk,
     * original_name =varchar(191):null,
     * status        =varchar(10):pending pending, verified, rejected
     * created_at    =timestamp:null
     * updated_at    =timestamp:null
     ****/

    public const TRADE_LICENSE = 'Trade License';
    public const NID = 'National ID (NID)';
    public const TIN = 'TIN Certificate';
    public const OTHER = 'Other';

    // The three papers every business is asked for, then any other paper.
    public const REQUIRED = [self::TRADE_LICENSE, self::NID, self::TIN];
    public const TYPES = [self::TRADE_LICENSE, self::NID, self::TIN, self::OTHER];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function isVerified(): bool
    {
        return $this->status == 'verified';
    }

    // [label, css class] of the status badge.
    public function statusBadge(): array
    {
        return match ($this->status) {
            'verified' => ['Verified', 'badge-soft-green', 'fa-check'],
            'rejected' => ['Rejected', 'badge-soft-red', 'fa-xmark'],
            default => ['Under review', 'badge-soft-amber', 'fa-clock'],
        };
    }

    // Whether the user has a verified paper of the type / an uploaded one waiting for review.
    public static function stateOf($userId, $type): ?string
    {
        $statuses = static::where('user_id', $userId)->where('type', $type)->pluck('status');

        if ($statuses->contains('verified')) {
            return 'verified';
        }
        return $statuses->contains('pending') ? 'pending' : null;
    }
}
