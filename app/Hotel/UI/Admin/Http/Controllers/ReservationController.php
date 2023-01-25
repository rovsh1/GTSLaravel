<?php

namespace GTS\Hotel\UI\Admin\Http\Controllers;

use Illuminate\Http\Request;

use GTS\Hotel\Infrastructure\Api\Reservation\ApiInterface as ReservationApiInterface;
use GTS\Hotel\UI\Admin\Http\Actions\Reservation as Actions;
use GTS\Shared\UI\Common\Http\Controllers\Controller;

class ReservationController extends Controller
{

    public function __construct(private ReservationApiInterface $reservationApi) { }

    public function reserveQuota(Request $request)
    {
        return (new Actions\ReserveQuota($this->reservationApi))->handle($request->post('room_id'), $request->post('data'));
    }
}
