<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Http\Requests\Hotel\BatchUpdateQuotaRequest;
use App\Admin\Http\Requests\Hotel\GetQuotaRequest;
use App\Admin\Http\Requests\Hotel\UpdateQuotaRequest;
use App\Admin\Http\Requests\Hotel\UpdateQuotaStatusRequest;
use App\Admin\Http\Resources\RoomQuota;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Models\Hotel\Room;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Hotel\QuotaAdapter;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Admin\View\Menus\HotelMenu;
use App\Core\Support\Http\Responses\AjaxResponseInterface;
use App\Core\Support\Http\Responses\AjaxSuccessResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuotaController extends Controller
{
    public function index(Request $request, Hotel $hotel): LayoutContract
    {
        $this->hotel($hotel);

        return Layout::title((string)$hotel)
            ->view('hotel.quotas.quotas', ['hotel' => $hotel]);
    }

    public function get(GetQuotaRequest $request, Hotel $hotel): JsonResponse
    {
        $quotas = match ($request->getAvailability()) {
            $request::AVAILABILITY_SOLD => QuotaAdapter::getSoldQuotas(
                $hotel->id,
                $request->getPeriod(),
                $request->getRoomId()
            ),
            $request::AVAILABILITY_STOPPED => QuotaAdapter::getStoppedQuotas(
                $hotel->id,
                $request->getPeriod(),
                $request->getRoomId()
            ),
            $request::AVAILABILITY_AVAILABLE => QuotaAdapter::getAvailableQuotas(
                $hotel->id,
                $request->getPeriod(),
                $request->getRoomId()
            ),
            default => QuotaAdapter::getQuotas($hotel->id, $request->getPeriod(), $request->getRoomId()),
        };

        return response()->json(RoomQuota::collection($quotas));
    }

    public function update(UpdateQuotaRequest $request, Hotel $hotel, Room $room): AjaxResponseInterface
    {
        foreach ($request->getDates() as $date) {
            QuotaAdapter::updateRoomQuota($room->id, $date, $request->getCount(), $request->getReleaseDays());
        }

        return new AjaxSuccessResponse();
    }

    public function openQuota(UpdateQuotaStatusRequest $request, Hotel $hotel, Room $room): AjaxResponseInterface
    {
        foreach ($request->getDates() as $date) {
            QuotaAdapter::openRoomQuota($room->id, $date);
        }

        return new AjaxSuccessResponse();
    }

    public function closeQuota(UpdateQuotaStatusRequest $request, Hotel $hotel, Room $room): AjaxResponseInterface
    {
        foreach ($request->getDates() as $date) {
            QuotaAdapter::closeRoomQuota($room->id, $date);
        }

        return new AjaxSuccessResponse();
    }

    public function resetQuota(UpdateQuotaStatusRequest $request, Hotel $hotel, Room $room): AjaxResponseInterface
    {
        foreach ($request->getDates() as $date) {
            QuotaAdapter::resetRoomQuota($room->id, $date);
        }

        return new AjaxSuccessResponse();
    }

    public function batchUpdateDateQuota(
        BatchUpdateQuotaRequest $request,
        Hotel $hotel,
    ): AjaxResponseInterface {
        foreach ($request->getPeriod() as $date) {
            //@todo переделать на норм запрос
            $isNeedUpdate = in_array($date->dayOfWeekIso, $request->getWeekDays());
            if (!$isNeedUpdate) {
                continue;
            }
            $action = $request->getAction();

            foreach ($request->getRoomIds() as $roomId) {
                if ($action === BatchUpdateQuotaRequest::OPEN) {
                    QuotaAdapter::openRoomQuota(
                        roomId: $roomId,
                        date: $date,
                    );
                } elseif ($action === BatchUpdateQuotaRequest::CLOSE) {
                    QuotaAdapter::closeRoomQuota(
                        roomId: $roomId,
                        date: $date
                    );
                }
            }
        }

        return new AjaxSuccessResponse();
    }

    private function hotel(Hotel $hotel): void
    {
        Breadcrumb::prototype('hotel')
            ->addUrl(route('hotels.show', $hotel), (string)$hotel)
            ->addUrl(route('hotels.quotas.index', $hotel), 'Квоты');

        Sidebar::submenu(new HotelMenu($hotel, 'quotas'));
    }
}
