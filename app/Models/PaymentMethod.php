<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    /********
     * Column:
     * id             =bigint(20):None,
     * user_id        =bigint(20):None,
     * type           =varchar(20):None one of TYPES,
     * account_number =varchar(40):None mobile number (01XXXXXXXXX) or bank account number,
     * account_kind   =varchar(20):null Personal, Agent, Merchant (mobile banking),
     * bank_name      =varchar(80):null (bank transfer),
     * branch         =varchar(80):null (bank transfer),
     * is_default     =tinyint(1):0
     * created_at     =timestamp:null
     * updated_at     =timestamp:null
     ****/

    public const BANK = 'Bank Transfer';
    public const MOBILE = ['bKash', 'Nagad', 'Rocket'];
    public const TYPES = ['bKash', 'Nagad', 'Rocket', self::BANK];
    public const KINDS = ['Personal', 'Agent', 'Merchant'];

    // Most methods one user can keep.
    public const LIMIT = 10;

    protected $casts = ['is_default' => 'boolean'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function isBank(): bool
    {
        return $this->type == self::BANK;
    }

    // "017XX XX3678" for a mobile account, "****4821" for a bank account.
    public function masked(): string
    {
        $number = $this->account_number;

        if ($this->isBank()) {
            return '****'.substr($number, -4);
        }
        return substr($number, 0, 3).'XX XX'.substr($number, -4);
    }

    // "bKash — 017XX XX3678", "Dutch-Bangla Bank — A/C ****4821"
    public function title(): string
    {
        return $this->isBank()
            ? ($this->bank_name ?: 'Bank').' — A/C '.$this->masked()
            : $this->type.' — '.$this->masked();
    }

    public function subline(): string
    {
        return $this->isBank() ? ($this->branch ?: 'Bank account') : (($this->account_kind ?: 'Personal').' account');
    }

    // [icon, background, color] of the round icon.
    public function look(): array
    {
        return match ($this->type) {
            'bKash' => ['fa-mobile-retro', '#e8f9ee', '#d6317b'],
            'Nagad' => ['fa-wallet', '#fff2e6', '#e8730f'],
            'Rocket' => ['fa-rocket', '#f1e9fa', '#8c3494'],
            default => ['fa-building-columns', 'var(--av-green-100)', 'var(--av-green-700)'],
        };
    }
}
