<?php

namespace Pkg\Supplier\Traveline\Http\Actions;

use Pkg\Supplier\Traveline\Http\Requests\GetRoomsAndRatePlansActionRequest;
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
