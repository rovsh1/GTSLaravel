<?php

namespace App\Api\Http\Traveline\Actions;

use App\Api\Http\Traveline\Requests\UpdateActionRequest;
use Sdk\Module\Contracts\PortGateway\PortGatewayInterface;

class UpdateAction
{
    public function __construct(private PortGatewayInterface $portGateway) {}

    public function handle(UpdateActionRequest $request)
    {
        return $this->portGateway->request('traveline/update', [
            'hotel_id' => $request->getHotelId(),
            'updates' => $request->getUpdates(),
        ]);
    }

}
