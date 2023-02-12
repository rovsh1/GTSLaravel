<?php

namespace GTS\Integration\Traveline\UI\Api\Http\Actions;

use GTS\Integration\Traveline\Infrastructure\Facade\HotelFacadeInterface;
use GTS\Integration\Traveline\UI\Api\Http\Requests\UpdateActionRequest;

class UpdateAction
{
    public function __construct(private HotelFacadeInterface $facade) {}

    public function handle(UpdateActionRequest $request)
    {
        $response = $this->facade->updateQuotasAndPlans($request->getHotelId(), $request->getUpdates());
    }

}
