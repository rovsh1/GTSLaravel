<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\View\Menus\HotelMenu;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request, Hotel $hotel)
    {
        $this->bootHotel($hotel);

        return Layout::title('Rooms')
            ->view('hotel.rooms');
    }

    private function bootHotel($hotel)
    {
        Breadcrumb::prototype('hotel')
            ->addUrl(route('hotels.show', $hotel), (string)$hotel)
            ->addUrl(route('hotels.rooms.index', $hotel), 'Номера');

        Sidebar::submenu(new HotelMenu($hotel, 'rooms'));
    }
}
