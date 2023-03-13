<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\View\Menus\HotelMenu;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request, Hotel $hotel)
    {
        dd($hotel);
    }

    private function bootHotel($model)
    {
        Sidebar::submenu(new HotelMenu($model, 'rooms'));
    }
}
