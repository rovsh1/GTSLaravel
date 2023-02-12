<?php

namespace GTS\Integration\Traveline\UI\Api\Http\Requests;

class UpdateActionRequest extends AbstractTravelineRequest
{
    public function rules()
    {
        return array_merge([
            'data.hotelId' => 'required|numeric',
            'data.updates' => 'required|array'
        ], parent::rules());
    }

    public function getHotelId(): int
    {
        return $this->getData()['hotelId'];
    }

    public function getUpdates(): array
    {
        return $this->getData()['updates'];
    }
}
