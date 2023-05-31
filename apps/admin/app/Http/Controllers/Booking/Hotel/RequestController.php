<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Booking\Hotel;

use App\Admin\Http\Requests\Booking\AddRoomGuestRequest;
use App\Admin\Http\Requests\Booking\AddRoomRequest;
use App\Admin\Http\Requests\Booking\DeleteRoomRequest;
use App\Admin\Http\Requests\Booking\RequestSendRequest;
use App\Admin\Http\Requests\Booking\UpdateRoomGuestRequest;
use App\Admin\Http\Requests\Booking\UpdateRoomRequest;
use App\Admin\Support\Facades\Booking\HotelAdapter;
use App\Core\Support\Http\Responses\AjaxErrorResponse;
use App\Core\Support\Http\Responses\AjaxResponseInterface;
use App\Core\Support\Http\Responses\AjaxSuccessResponse;

class RequestController
{
    public function sendRequest(RequestSendRequest $request, int $id): AjaxResponseInterface
    {
        try {

        } catch (\Throwable $e) {
            //@todo отлов доменных эксепшнов
            dd($e);
        }
        return new AjaxSuccessResponse();
    }

}
