<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Models\Hotel\Rule;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Admin\View\Menus\HotelMenu;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(Request $request, Hotel $hotel): LayoutContract
    {
        $this->hotel($hotel);

        return Layout::title((string)$hotel)
            ->view('hotel.settings.settings', [
                'model' => $hotel,
                'createRuleUrl' => route('hotels.rules.create', $hotel),
                'rulesGrid' => $this->rulesGridFactory($hotel->id),
                'contracts' => $hotel->contracts
            ]);
    }

    private function rulesGridFactory(int $hotelId): GridContract
    {
        return Grid::header(false)
            ->edit(fn($r) => route('hotels.rules.edit', [$hotelId, $r->id]))
            ->text('name')
            ->data(
                Rule::whereHotelId($hotelId)
            );
    }

    private function hotel(Hotel $hotel): void
    {
        Breadcrumb::prototype('hotel')
            ->addUrl(route('hotels.show', $hotel), (string)$hotel)
            ->addUrl(route('hotels.settings.index', $hotel), 'Условия размещения');

        Sidebar::submenu(new HotelMenu($hotel, 'settings'));
    }
}
