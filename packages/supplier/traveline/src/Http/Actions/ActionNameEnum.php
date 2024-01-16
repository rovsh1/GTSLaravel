<?php

namespace Pkg\Supplier\Traveline\Http\Actions;

use Illuminate\Http\Request;
use Pkg\Supplier\Traveline\Http\Request\ConfirmBookingsActionRequest;
use Pkg\Supplier\Traveline\Http\Request\GetReservationsActionRequest;
use Pkg\Supplier\Traveline\Http\Request\GetRoomsAndRatePlansActionRequest;
use Pkg\Supplier\Traveline\Http\Request\UpdateActionRequest;

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

    public function getAction(): string
    {
        return match ($this) {
            self::GetRoomsAndRatePlans => GetRoomsAndRatePlansAction::class,
            self::GetBookings => GetReservationsAction::class,
            self::ConfirmBookings => ConfirmBookingsAction::class,
            self::Update => UpdateAction::class,
        };
    }

}
