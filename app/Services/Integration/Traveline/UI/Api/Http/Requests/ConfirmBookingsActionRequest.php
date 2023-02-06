<?php

namespace GTS\Services\Integration\Traveline\UI\Api\Http\Requests;

class ConfirmBookingsActionRequest extends AbstractTravelineRequest
{
    public function rules()
    {
        return array_merge([

        ], parent::rules());
    }
}
