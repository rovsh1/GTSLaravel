<?php

namespace Pkg\Supplier\Traveline\Http\Request;

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
