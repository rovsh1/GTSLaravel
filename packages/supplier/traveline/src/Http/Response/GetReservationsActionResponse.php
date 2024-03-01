<?php

namespace Pkg\Supplier\Traveline\Http\Response;

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
