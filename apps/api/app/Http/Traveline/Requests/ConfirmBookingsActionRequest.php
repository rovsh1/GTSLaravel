<?php

namespace App\Api\Http\Traveline\Requests;

class ConfirmBookingsActionRequest extends AbstractTravelineRequest
{
    public function rules()
    {
        return array_merge([
            'data.confirmBookings' => 'required|array',
            'data.confirmBookings.*.number' => 'required|numeric',
            'data.confirmBookings.*.externalNumber' => 'required',
            'data.confirmBookings.*.status' => 'required|string',
        ], parent::rules());
    }

    public function getReservations(): array
    {
        return $this->getData()['confirmBookings'];
    }
}
