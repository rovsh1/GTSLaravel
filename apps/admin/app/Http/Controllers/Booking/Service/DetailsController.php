<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Booking\Service;

use App\Admin\Http\Requests\Booking\Service\CreateTransferFromAirportRequest;
use App\Admin\Http\Requests\Booking\Service\CreateTransferToAirportRequest;
use App\Core\Support\Http\Responses\AjaxResponseInterface;
use App\Core\Support\Http\Responses\AjaxSuccessResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use Module\Shared\Enum\ServiceTypeEnum;

class DetailsController
{
    public function create(Request $request, int $bookingId, int $serviceType): AjaxResponseInterface
    {
        Validator::make(
            ['service_type' => $serviceType],
            ['service_type' => ['required', new Enum(ServiceTypeEnum::class)]]
        )->validate();

        return new AjaxSuccessResponse();
    }

    public function createTransferToAirport(
        CreateTransferToAirportRequest $request,
        int $bookingId
    ): AjaxResponseInterface {
        return new AjaxSuccessResponse();
    }

    public function createTransferFromAirport(
        CreateTransferFromAirportRequest $request,
        int $bookingId
    ): AjaxResponseInterface {
        return new AjaxSuccessResponse();
    }
}
