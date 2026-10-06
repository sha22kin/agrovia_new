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
     * created_at       =timestamp:null
     * updated_at       =timestamp:null
     ****/

    public const TYPES = ['Wholesaler', 'Retailer', 'Distributor', 'Importer / Exporter'];

    public const EMPLOYEES = ['1 - 5', '6 - 20', '21 - 50', '50+'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
