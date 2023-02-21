<?php

namespace App\Api\Http\Traveline\Actions;

use App\Api\Http\Traveline\Requests\UpdateActionRequest;
use Module\Integration\Traveline\Domain\Api\Response\EmptySuccessResponse;
use Module\Integration\Traveline\Domain\Api\Response\HotelNotConnectedToChannelManagerResponse;
use Module\Integration\Traveline\Domain\Exception\HotelNotConnectedException;
use Module\Shared\Infrastructure\Adapter\PortGatewayInterface;

class UpdateAction
{
    public function __construct(private PortGatewayInterface $portGateway) {}

    public function handle(UpdateActionRequest $request)
    {
        try {
            $response = $this->portGateway->request('traveline/update', [
                'hotel_id' => $request->getHotelId(),
                'updates' => $request->getUpdates(),
            ]);
        } catch (HotelNotConnectedException $exception) {
            return new HotelNotConnectedToChannelManagerResponse();
        }
        return new EmptySuccessResponse();
    }

}
