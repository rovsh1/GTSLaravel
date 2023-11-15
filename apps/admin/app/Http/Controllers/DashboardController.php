<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Support\Facades\Sitemap;

class DashboardController extends Controller
{
    public function index()
    {
        //Sitemap::
        return redirect(route('hotel-booking.index'));
    }
}
