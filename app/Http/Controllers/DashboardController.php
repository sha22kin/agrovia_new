<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

// The old single "customer" dashboard was split into a buyer and a seller dashboard.
// These routes keep the old /customer/... URLs (bookmarks, mails) working by sending
// the user to the matching page of their own dashboard.
class DashboardController extends Controller
{
    public function home(){
        return redirect(dashboardUrl());
    }

    public function profile(){
        return redirect()->route($this->owner().'.profile');
    }

    public function changePassword(){
        return redirect()->route($this->owner().'.changePassword');
    }

    public function listings(){
        return redirect()->route('seller.listings');
    }

    public function listingsAction($action,$id=null){
        return redirect()->route('seller.listingsAction',array_filter([$action,$id]));
    }

    public function payments(){
        return redirect()->route('seller.payments');
    }

    private function owner(){
        return Auth::user()->isSeller()?'seller':'buyer';
    }
}
