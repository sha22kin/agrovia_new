<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessProfile extends Model
{
    /********
     * Column:
     * id               =bigint(20):None,
     * user_id          =bigint(20):None unique,
     * business_type    =varchar(30):null one of TYPES,
     * license_no       =varchar(60):null trade license,
     * tin_bin          =varchar(60):null,
     * established_year =smallint:null,
     * employees        =varchar(20):null one of EMPLOYEES,
     * address          =varchar(191):null,
     * areas            =varchar(300):null comma separated,
     * land_area        =decimal(10,2):null total land of the seller,
     * land_unit        =varchar(10):null one of LAND_UNITS,
     * main_crops       =varchar(300):null comma separated crops or products,
     * created_at       =timestamp:null
     * updated_at       =timestamp:null
     ****/

    public const TYPES = ['Wholesaler', 'Retailer', 'Distributor', 'Importer / Exporter'];

    public const EMPLOYEES = ['1 - 5', '6 - 20', '21 - 50', '50+'];

    public const LAND_UNITS = ['Bigha', 'Acre'];

    // "12.5 Bigha", null when no land is given.
    public function landLabel(): ?string
    {
        if ($this->land_area === null) {
            return null;
        }
        $number = rtrim(rtrim(number_format((float) $this->land_area, 2, '.', ''), '0'), '.');
        return trim($number.' '.($this->land_unit ?: 'Bigha'));
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
