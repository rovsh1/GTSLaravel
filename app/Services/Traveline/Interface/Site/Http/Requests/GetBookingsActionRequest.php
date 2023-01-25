<?php

namespace GTS\Services\Traveline\Interface\Site\Http\Requests;

class GetBookingsActionRequest extends AbstractTravelineRequest
{

    public const ACTION_NAME = 'get-bookings';

    public function rules()
    {
        return array_merge([

        ], parent::rules());
    }
}
