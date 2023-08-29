<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Booking\Order;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Http\Requests\Order\Tourist\AddRequest;
use App\Admin\Http\Requests\Order\Tourist\UpdateRequest;
use App\Admin\Support\Facades\Booking\OrderAdapter;
use App\Core\Support\Http\Responses\AjaxResponseInterface;
use App\Core\Support\Http\Responses\AjaxSuccessResponse;
use Illuminate\Http\JsonResponse;

class TouristController extends Controller
{
    public function list(int $orderId): JsonResponse
    {
        $tourists = OrderAdapter::getTourists($orderId);

        return response()->json($tourists);
    }

    public function addTourist(int $orderId, AddRequest $request): JsonResponse
    {
        $tourist = OrderAdapter::addTourist(
            orderId: $orderId,
            fullName: $request->getFullName(),
            countryId: $request->getCountryId(),
            isAdult: $request->getIsAdult(),
            gender: $request->getGender(),
            age: $request->getAge()
        );

        return response()->json($tourist);
    }

    public function updateTourist(int $orderId, int $touristId, UpdateRequest $request): AjaxResponseInterface
    {
        OrderAdapter::updateTourist(
            touristId: $touristId,
            fullName: $request->getFullName(),
            countryId: $request->getCountryId(),
            isAdult: $request->getIsAdult(),
            gender: $request->getGender(),
            age: $request->getAge()
        );

        return new AjaxSuccessResponse();
    }

    public function deleteTourist(int $orderId, int $touristId): AjaxResponseInterface
    {
        OrderAdapter::deleteTourist($touristId);

        return new AjaxSuccessResponse();
    }
}
