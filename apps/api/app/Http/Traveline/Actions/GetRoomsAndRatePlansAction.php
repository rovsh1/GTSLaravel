<?php

namespace App\Api\Http\Traveline\Actions;

use App\Api\Http\Traveline\Requests\GetRoomsAndRatePlansActionRequest;
use Sdk\Module\Contracts\PortGateway\PortGatewayInterface;

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
