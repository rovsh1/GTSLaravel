<?php

namespace App\Api\Http\Traveline\Actions;

use App\Api\Http\Traveline\Requests\UpdateActionRequest;
use Module\Shared\Infrastructure\Adapter\PortGatewayInterface;

class UpdateAction
{
    public function __construct(private PortGatewayInterface $portGateway) {}

    public function handle(UpdateActionRequest $request)
    {
        \Log::debug('[App\Api\Http\Traveline\Actions\UpdateAction::handle] Request', $request->toArray());

        return $this->portGateway->request('traveline/update', [
            'hotel_id' => $request->getHotelId(),
            'updates' => $request->getUpdates(),
        ]);
    }

}
