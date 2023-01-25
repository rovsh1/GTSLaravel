<?php

namespace GTS\Services\Traveline\UI\Api\Http\Requests;

class UpdateActionRequest extends AbstractTravelineRequest
{

    public const ACTION_NAME = 'update';

    public function rules()
    {
        return array_merge([

        ], parent::rules());
    }
}
