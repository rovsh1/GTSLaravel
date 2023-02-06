<?php

namespace GTS\Services\Integration\Traveline\UI\Api\Http\Actions;

use GTS\Services\Integration\Traveline\UI\Api\Http\Requests\ConfirmBookingsActionRequest;
use GTS\Services\Integration\Traveline\UI\Api\Http\Requests\GetReservationsActionRequest;
use GTS\Services\Integration\Traveline\UI\Api\Http\Requests\GetRoomsAndRatePlansActionRequest;
use GTS\Services\Integration\Traveline\UI\Api\Http\Requests\UpdateActionRequest;
use Illuminate\Http\Request;

enum ActionNameEnum: string
{

    case GetRoomsAndRatePlans = 'get-rooms-and-rate-plans';
    case GetBookings = 'get-bookings';
    case ConfirmBookings = 'confirm-bookings';
    case Update = 'update';

    /**
     * @return GetRoomsAndRatePlansActionRequest|GetReservationsActionRequest|ConfirmBookingsActionRequest|UpdateActionRequest
     */
    public function getRequest(Request $request)
    {
        return match ($this) {
            self::GetRoomsAndRatePlans => GetRoomsAndRatePlansActionRequest::createFrom($request),
            self::GetBookings => GetReservationsActionRequest::createFrom($request),
            self::ConfirmBookings => ConfirmBookingsActionRequest::createFrom($request),
            self::Update => UpdateActionRequest::createFrom($request),
        };
    }

    /**
     * @return GetRoomsAndRatePlansAction|GetReservationsAction|ConfirmBookingsAction|UpdateAction
     */
    public function getAction()
    {
        return match ($this) {
            self::GetRoomsAndRatePlans => app(GetRoomsAndRatePlansAction::class),
            self::GetBookings => app(GetReservationsAction::class),
            self::ConfirmBookings => app(ConfirmBookingsAction::class),
            self::Update => app(UpdateAction::class),
        };
    }

}
