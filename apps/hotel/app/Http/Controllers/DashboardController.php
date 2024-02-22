<?php

namespace App\Hotel\Http\Controllers;

use App\Hotel\Support\Http\AbstractController;

class DashboardController extends AbstractController
{
    public function index()
    {
        return redirect(route('booking.index'));
    }
}
