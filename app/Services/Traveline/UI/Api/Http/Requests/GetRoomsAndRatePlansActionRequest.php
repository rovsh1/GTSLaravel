<?php

namespace GTS\Services\Traveline\UI\Api\Http\Requests;

class GetRoomsAndRatePlansActionRequest extends AbstractTravelineRequest
{

    public const ACTION_NAME = 'get-rooms-and-rate-plans';

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
