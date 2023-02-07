<?php

namespace GTS\Integration\Traveline\UI\Api\Http\Requests;

class ConfirmBookingsActionRequest extends AbstractTravelineRequest
{
    public function rules()
    {
        return array_merge([

        ], parent::rules());
    }
}
