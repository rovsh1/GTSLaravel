<?php

namespace App\Api\Http\Traveline\Actions;

use App\Api\Http\Traveline\Requests\GetRoomsAndRatePlansActionRequest;
use Module\Shared\Infrastructure\Adapter\PortGatewayInterface;

class GetRoomsAndRatePlansAction
{
    public function __construct(private PortGatewayInterface $portGateway) {}

    public function handle(GetRoomsAndRatePlansActionRequest $request)
    {
        return $this->portGateway->request('traveline/getRoomsAndRatePlans', [
            'hotel_id' => $request->getHotelId()
        ]);
    }

}
