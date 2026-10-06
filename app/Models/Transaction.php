<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //Models Information Data
    /********
     *
     *
     * Column:
     *
     * id               =bigint(20):None,
     * user_id          =bigint(20):null,
     * provider_name    =varchar(255):null,
     * provider_id      =varchar(255):null,
     * provider_token   =varchar(255):null,
     * provider_img_url =varchar(255):null,
     * created_at       =timestamp:null
     * updated_at       =timestamp:null
     *
     *
     *
     ****/

    // The status column is a free string (Pending, Success, Fail, Cancel). The buyer order page groups
    // the values under the names shown to the buyer.
    public const STATUS_GROUPS = [
        'Pending' => ['Pending'],
        'Delivered' => ['Delivered'],
        'Completed' => ['Success','Completed'],
        'Cancelled' => ['Cancel','Fail'],
    ];

    private const STATUS_LABELS = ['Success' => 'Completed', 'Cancel' => 'Cancelled', 'Fail' => 'Failed'];

    // Group the status belongs to (Pending, Delivered, Completed, Cancelled), null for an unknown status.
    public function statusGroup(): ?string
    {
        foreach (self::STATUS_GROUPS as $group => $statuses) {
            if (in_array($this->status, $statuses, true)) {
                return $group;
            }
        }
        return null;
    }

    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ($this->status ?: 'Unknown');
    }
}
