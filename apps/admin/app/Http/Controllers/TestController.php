<?php

namespace App\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Module\Shared\Infrastructure\Adapter\PortGatewayInterface;

class TestController extends Controller
{
    public function __construct(private readonly PortGatewayInterface $portGateway) {}

    public function form(Request $request)
    {
        $f = $this->portGateway->request('HotelReservation/reservation-cancel', ['id' => '1']);
        dd($f);
        return app(\App\Admin\Http\Actions\Test\FormAction::class)->handle();
    }
}
