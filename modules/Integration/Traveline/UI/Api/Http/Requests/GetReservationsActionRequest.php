<?php

namespace Module\Integration\Traveline\UI\Api\Http\Requests;

use Carbon\Carbon;
use Carbon\CarbonInterface;

class GetReservationsActionRequest extends AbstractTravelineRequest
{
    public function rules()
    {
        return array_merge([
            'data.startTime' => 'nullable|date',
            'data.number' => 'nullable|numeric',
            'data.hotelId' => 'nullable|numeric',
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
