<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\View\Menus\HotelMenu;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function index(Request $request, Hotel $hotel)
    {
        $this->bootHotel($hotel);

        return Layout::title('Rooms')
            ->view('hotel.rooms', [
                'editAllowed' => true,
                'deleteAllowed' => true,
                'createUrl' => Acl::isCreateAllowed('hotel') ? route('hotels.rooms.create', $hotel) : null,
                'rooms' => $hotel->rooms
            ]);
    }

    private function bootHotel($hotel)
    {
        Breadcrumb::prototype('hotel')
            ->addUrl(route('hotels.show', $hotel), (string)$hotel)
            ->addUrl(route('hotels.prices.index', $hotel), 'Цены');

        Sidebar::submenu(new HotelMenu($hotel, 'prices'));
    }
}
