<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    /********
     * Column:
     * id          =bigint(20):None,
     * user_id     =bigint(20):None,
     * label       =varchar(60):None e.g. Warehouse, Home, Billing,
     * address     =varchar(300):None,
     * phone       =varchar(30):null,
     * district_id =bigint(20):null countries.id (type 3),
     * is_default  =tinyint(1):0
     * created_at  =timestamp:null
     * updated_at  =timestamp:null
     ****/

    // Most addresses one user can keep.
    public const LIMIT = 10;

    protected $casts = ['is_default' => 'boolean'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function district(){
        return $this->belongsTo(Country::class, 'district_id');
    }

    // "House 12, Kalitala Road, Bogura, Bangladesh"
    public function fullLine(): string
    {
        return collect([$this->address, $this->district?->name, 'Bangladesh'])->filter()->implode(', ');
    }

    // Font Awesome icon picked from the label.
    public function icon(): string
    {
        $label = mb_strtolower($this->label);

        return match (true) {
            str_contains($label, 'warehouse') || str_contains($label, 'godown') => 'fa-warehouse',
            str_contains($label, 'home') || str_contains($label, 'house') => 'fa-house',
            str_contains($label, 'bill') => 'fa-file-invoice',
            str_contains($label, 'shop') || str_contains($label, 'office') => 'fa-shop',
            default => 'fa-location-dot',
        };
    }
}
