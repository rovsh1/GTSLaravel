<?php

namespace Module\Hotel\UI\Admin\Http\Controllers;

use App\Core\Http\Controllers\Controller;
use GTS\Hotel\Infrastructure\Api\Reservation\ApiInterface as ReservationApiInterface;
use Illuminate\Http\Request;

class ReservationController extends Controller
{

    public function __construct(private ReservationApiInterface $reservationApi) { }

    public function reserveQuota(Request $request)
    {
        return (new \Module\Hotel\UI\Admin\Http\Actions\Reservation\ReserveQuota($this->reservationApi))->handle($request->post('room_id'), $request->post('data'));
    }
}
