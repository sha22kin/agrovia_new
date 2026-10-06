<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // Renders a page shared by the dashboards (resources/views/dashboard/partials/<partial>)
    // inside the layout of the role ('buyer', 'seller' or 'expert').
    protected function dashboardPage($role, $title, $partial, $data = [])
    {
        $theme = match ($role) {
            'seller' => sellerTheme(),
            'expert' => expertTheme(),
            default => buyerTheme(),
        };
        $layout = $theme.'layouts.app';

        return view('dashboard.page', array_merge($data, compact('role', 'title', 'partial', 'layout')));
    }
}
