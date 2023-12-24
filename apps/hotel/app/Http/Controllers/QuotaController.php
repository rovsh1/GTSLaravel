<?php

namespace App\Hotel\Http\Controllers;

use App\Hotel\Http\Requests\BatchUpdateQuotaRequest;
use App\Hotel\Http\Requests\GetQuotaRequest;
use App\Hotel\Http\Requests\UpdateQuotaRequest;
use App\Hotel\Http\Requests\UpdateQuotaStatusRequest;
use App\Hotel\Http\Resources\RoomQuota;
use App\Hotel\Models\Room;
use App\Hotel\Support\Facades\Layout;
use App\Hotel\Support\Facades\QuotaAdapter;
use App\Hotel\Support\View\LayoutBuilder as LayoutContract;
use App\Shared\Http\Responses\AjaxResponseInterface;
use App\Shared\Http\Responses\AjaxSuccessResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuotaController extends AbstractHotelController
{
    public function index(Request $request): LayoutContract
    {
        return Layout::title($this->getPageHeader())
            ->view('quotas.quotas', ['hotel' => $this->getHotel()]);
    }

    public function get(GetQuotaRequest $request): JsonResponse
    {
        $hotelId = $this->getHotel()->id;
        $quotas = match ($request->getAvailability()) {
            $request::AVAILABILITY_SOLD => QuotaAdapter::getSoldQuotas(
                $hotelId,
                $request->getPeriod(),
                $request->getRoomId()
            ),
            $request::AVAILABILITY_STOPPED => QuotaAdapter::getStoppedQuotas(
                $hotelId,
                $request->getPeriod(),
                $request->getRoomId()
            ),
            $request::AVAILABILITY_AVAILABLE => QuotaAdapter::getAvailableQuotas(
                $hotelId,
                $request->getPeriod(),
                $request->getRoomId()
            ),
            default => QuotaAdapter::getQuotas($hotelId, $request->getPeriod(), $request->getRoomId()),
        };

        return response()->json(RoomQuota::collection($quotas));
    }

    public function update(UpdateQuotaRequest $request, Room $room): AjaxResponseInterface
    {
        foreach ($request->getDates() as $date) {
            QuotaAdapter::updateRoomQuota($room->id, $date, $request->getCount(), $request->getReleaseDays());
        }

        return new AjaxSuccessResponse();
    }

    public function openQuota(UpdateQuotaStatusRequest $request, Room $room): AjaxResponseInterface
    {
        foreach ($request->getDates() as $date) {
            QuotaAdapter::openRoomQuota($room->id, $date);
        }

        return new AjaxSuccessResponse();
    }

    public function closeQuota(UpdateQuotaStatusRequest $request, Room $room): AjaxResponseInterface
    {
        foreach ($request->getDates() as $date) {
            QuotaAdapter::closeRoomQuota($room->id, $date);
        }

        return new AjaxSuccessResponse();
    }

    public function resetQuota(UpdateQuotaStatusRequest $request, Room $room): AjaxResponseInterface
    {
        foreach ($request->getDates() as $date) {
            QuotaAdapter::resetRoomQuota($room->id, $date);
        }

        return new AjaxSuccessResponse();
    }

    public function batchUpdateDateQuota(
        BatchUpdateQuotaRequest $request,
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
}
