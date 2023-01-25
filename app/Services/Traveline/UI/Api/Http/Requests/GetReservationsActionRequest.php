<?php

namespace GTS\Services\Traveline\UI\Api\Http\Requests;

class GetReservationsActionRequest extends AbstractTravelineRequest
{

    public function rules()
    {
        return array_merge([

        ], parent::rules());
    }
}
