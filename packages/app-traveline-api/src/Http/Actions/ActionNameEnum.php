<?php

namespace Pkg\App\Traveline\Http\Actions;

use Illuminate\Http\Request;
use Pkg\App\Traveline\Http\Request\ConfirmBookingsActionRequest;
use Pkg\App\Traveline\Http\Request\GetReservationsActionRequest;
use Pkg\App\Traveline\Http\Request\GetRoomsAndRatePlansActionRequest;
use Pkg\App\Traveline\Http\Request\UpdateActionRequest;

enum ActionNameEnum: string
{
    case GetRoomsAndRatePlans = 'get-rooms-and-rate-plans';
    case GetBookings = 'get-bookings';
    case ConfirmBookings = 'confirm-bookings';
    case Update = 'update';
}
