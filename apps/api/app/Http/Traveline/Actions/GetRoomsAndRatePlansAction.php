<?php

namespace App\Api\Http\Traveline\Actions;

use App\Api\Http\Traveline\Requests\GetRoomsAndRatePlansActionRequest;
use Module\Integration\Traveline\Domain\Api\Response\GetRoomsAndRatePlansActionResponse;
use Module\Integration\Traveline\Domain\Api\Response\HotelNotExistInChannelResponse;
use Module\Integration\Traveline\Domain\Exception\HotelNotConnectedException;
use Module\Shared\Infrastructure\Adapter\PortGatewayInterface;

class GetRoomsAndRatePlansAction
{
    public function __construct(private PortGatewayInterface $portGateway) {}

    public function handle(GetRoomsAndRatePlansActionRequest $request)
    {
        try {
            $roomsAndRatePlans = $this->portGateway->request('traveline/getRoomsAndRatePlans', [
                'hotel_id' => $request->getHotelId()
            ]);
        } catch (HotelNotConnectedException $exception) {
            return new HotelNotExistInChannelResponse();
        }
        return new GetRoomsAndRatePlansActionResponse($roomsAndRatePlans);
    }

}
