<?php

namespace GTS\Services\Traveline\Interface\Site\Http\Requests;

class GetRoomsAndRatePlansActionRequest extends AbstractTravelineRequest
{

    public const ACTION_NAME = 'get-rooms-and-rate-plans';

    public function rules()
    {
        return array_merge([

        ], parent::rules());
    }
}
