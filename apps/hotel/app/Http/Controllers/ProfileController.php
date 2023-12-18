<?php

namespace App\Hotel\Http\Controllers;

use App\Hotel\Support\Http\AbstractController;
use Illuminate\Http\RedirectResponse;

class ProfileController extends AbstractController
{
    public function index(): RedirectResponse
    {
        return response()->redirect('home');
    }
}
