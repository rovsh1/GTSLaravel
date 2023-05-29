<?php

namespace Module\Integration\Traveline\Domain\Api\Response;

class GetReservationsActionResponse extends AbstractTravelineResponse
{
    public function jsonSerialize(): mixed
    {
        return [
            'success' => $this->success,
            'data' => [
                'bookings' => $this->data
            ]
        ];
    }
}
