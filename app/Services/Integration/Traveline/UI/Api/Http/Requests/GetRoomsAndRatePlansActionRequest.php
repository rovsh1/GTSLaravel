<?php

namespace GTS\Services\Integration\Traveline\UI\Api\Http\Requests;

class GetRoomsAndRatePlansActionRequest extends AbstractTravelineRequest
{
    public function rules()
    {
        return array_merge([
            'data.hotelId' => 'required|numeric'
        ], parent::rules());
    }

    public function getHotelId(): int
    {
        return $this->getData()['hotelId'];
    }
}
