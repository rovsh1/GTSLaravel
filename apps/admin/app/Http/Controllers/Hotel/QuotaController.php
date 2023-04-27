<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Http\Requests\Hotel\GetQuotaRequest;
use App\Admin\Http\Requests\Hotel\UpdateQuotaRequest;
use App\Admin\Http\Resources\RoomQuota;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Hotel\QuotaAdapter;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Admin\View\Menus\HotelMenu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuotaController extends Controller
{
    public function index(Request $request, Hotel $hotel): LayoutContract
    {
        $this->hotel($hotel);

        return Layout::title((string)$hotel)
            ->view('hotel.quotas.quotas');
    }

    public function get(GetQuotaRequest $request, Hotel $hotel): JsonResponse
    {
        $quotas = QuotaAdapter::getRoomQuota($request->getRoomId(), $request->getPeriod());
        return response()->json(RoomQuota::collection($quotas));
    }

    public function update(UpdateQuotaRequest $request, Hotel $hotel): JsonResponse
    {
        //@todo
        return response()->json();
    }

    private function hotel(Hotel $hotel): void
    {
        Breadcrumb::prototype('hotel')
            ->addUrl(route('hotels.show', $hotel), (string)$hotel)
            ->addUrl(route('hotels.quotas.index', $hotel), 'Квоты');

        Sidebar::submenu(new HotelMenu($hotel, 'quotas'));
    }
}
