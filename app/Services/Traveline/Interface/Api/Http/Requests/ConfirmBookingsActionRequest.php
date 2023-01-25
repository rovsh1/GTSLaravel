<?php

namespace GTS\Services\Traveline\Interface\Api\Http\Requests;

class ConfirmBookingsActionRequest extends AbstractTravelineRequest
{

    public const ACTION_NAME = 'confirm-bookings';

    public function rules()
    {
        return array_merge([

        ], parent::rules());
    }
}
