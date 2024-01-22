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
}
