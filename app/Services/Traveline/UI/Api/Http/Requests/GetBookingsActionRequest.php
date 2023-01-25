<?php

namespace GTS\Services\Traveline\UI\Api\Http\Requests;

class GetBookingsActionRequest extends AbstractTravelineRequest
{

    public function rules()
    {
        return array_merge([

        ], parent::rules());
    }
}
