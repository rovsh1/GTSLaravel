<?php

namespace GTS\Services\Traveline\UI\Api\Http\Actions;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use GTS\Services\Traveline\UI\Api\Http\Requests\ConfirmBookingsActionRequest;
use GTS\Services\Traveline\UI\Api\Http\Requests\GetBookingsActionRequest;
use GTS\Services\Traveline\UI\Api\Http\Requests\GetRoomsAndRatePlansActionRequest;
use GTS\Services\Traveline\UI\Api\Http\Requests\UpdateActionRequest;

class IndexAction
{
    const GetRoomsAndRatePlans = 'get-rooms-and-rate-plans';
    const GetBookings = 'get-bookings';
    const ConfirmBookings = 'confirm-bookings';
    const Update = 'update';

    public function handle(Request $request)
    {
        return match ($request->get('action')) {
            self::GetRoomsAndRatePlans => $this->action($request, GetRoomsAndRatePlansActionRequest::class, GetRoomsAndRatePlansAction::class),
            self::GetBookings => $this->action($request, GetBookingsActionRequest::class, GetBookingsAction::class),
            self::ConfirmBookings => $this->action($request, ConfirmBookingsActionRequest::class, ConfirmBookingsAction::class),
            self::Update => $this->action($request, UpdateActionRequest::class, UpdateAction::class),
            default => throw new BadRequestHttpException('Unknown Traveline request')
        };
    }

    private function action($request, $requestClass, $actionClass)
    {
        $parsedRequest = $requestClass::createFrom($request);

        return app($actionClass)->handle($parsedRequest);
    }

}
