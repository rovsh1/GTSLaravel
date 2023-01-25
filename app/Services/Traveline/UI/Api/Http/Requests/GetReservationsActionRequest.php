<?php

namespace GTS\Services\Traveline\UI\Api\Http\Requests;

use Carbon\Carbon;
use Carbon\CarbonInterface;

class GetReservationsActionRequest extends AbstractTravelineRequest
{

    public function rules()
    {
        return array_merge([
            'data.startTime' => 'nullalbe|date',
            'data.number' => 'nullalbe|numeric',
            'data.hotelId' => 'nullalbe|numeric',
        ], parent::rules());
    }

    public function getHotelId(): ?int
    {
        return $this->getData()['hotelId'] ?? null;
    }

    public function getStartTime(): ?CarbonInterface
    {
        $date = $this->getData()['startTime'] ?? null;
        if ($date === null) {
            return null;
        }

        return new Carbon($date);
    }

    public function getReservationId(): ?int
    {
        return $this->getData()['number'] ?? null;
    }
}
