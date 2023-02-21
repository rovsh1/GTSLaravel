<?php

namespace Module\Integration\Traveline\UI\Api\Http\Actions;

use Module\Integration\Traveline\Domain\Api\Response\EmptySuccessResponse;
use Module\Integration\Traveline\Domain\Api\Response\HotelNotConnectedToChannelManagerResponse;
use Module\Integration\Traveline\Domain\Exception\HotelNotConnectedException;
use Module\Integration\Traveline\Infrastructure\Facade\HotelFacadeInterface;
use Module\Integration\Traveline\UI\Api\Http\Requests\UpdateActionRequest;

class UpdateAction
{
    public function __construct(private HotelFacadeInterface $facade) {}

    public function handle(UpdateActionRequest $request)
    {
        try {
            $response = $this->facade->updateQuotasAndPlans($request->getHotelId(), $request->getUpdates());
        } catch (HotelNotConnectedException $exception) {
            return new HotelNotConnectedToChannelManagerResponse();
        }
        return new EmptySuccessResponse();
    }

}
