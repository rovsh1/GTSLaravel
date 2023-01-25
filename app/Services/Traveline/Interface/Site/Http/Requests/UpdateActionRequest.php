<?php

namespace GTS\Services\Traveline\Interface\Site\Http\Requests;

class UpdateActionRequest extends AbstractTravelineRequest
{

    public const ACTION_NAME = 'update';

    public function rules()
    {
        return array_merge([

        ], parent::rules());
    }
}
