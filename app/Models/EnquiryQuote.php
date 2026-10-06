<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnquiryQuote extends Model
{
    /********
     * Column:
     * id         =bigint(20):None,
     * enquiry_id =bigint(20):None,
     * user_id    =bigint(20):null the seller,
     * price      =decimal(12,2):null,
     * message    =text:null,
     * created_at =timestamp:null
     * updated_at =timestamp:null
     ****/

    public function enquiry(){
        return $this->belongsTo(Enquiry::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
