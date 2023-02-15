<?php

namespace GTS\Integration\Traveline\UI\Api\Http\Actions;

use GTS\Integration\Traveline\Domain\Api\Response\EmptySuccessResponse;
use GTS\Integration\Traveline\Domain\Api\Response\HotelNotConnectedToChannelManagerResponse;
use GTS\Integration\Traveline\Domain\Exception\HotelNotConnectedException;
use GTS\Integration\Traveline\Infrastructure\Facade\HotelFacadeInterface;
use GTS\Integration\Traveline\UI\Api\Http\Requests\UpdateActionRequest;

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
