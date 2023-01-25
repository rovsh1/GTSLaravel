<?php

namespace GTS\Services\Traveline\UI\Api\Http\Actions;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use GTS\Services\Traveline\UI\Api\Http\Requests\ConfirmBookingsActionRequest;
use GTS\Services\Traveline\UI\Api\Http\Requests\GetBookingsActionRequest;
use GTS\Services\Traveline\UI\Api\Http\Requests\GetRoomsAndRatePlansActionRequest;
use GTS\Services\Traveline\UI\Api\Http\Requests\UpdateActionRequest;

class IndexAction
{

    public function handle(Request $request)
    {
        $action = $request->get('action');
        $responseData = [];
        $parsedRequest = null;
        if ($action === GetRoomsAndRatePlansActionRequest::ACTION_NAME) {
            $parsedRequest = GetRoomsAndRatePlansActionRequest::createFrom($request);
            $responseData = app(GetRoomsAndRatePlansAction::class)->handle($parsedRequest);
        }
        if ($action === GetBookingsActionRequest::ACTION_NAME) {
            $parsedRequest = GetBookingsActionRequest::createFrom($request);
            $responseData = app(GetBookingsAction::class)->handle($parsedRequest);
        }
        if ($action === ConfirmBookingsActionRequest::ACTION_NAME) {
            $parsedRequest = ConfirmBookingsActionRequest::createFrom($request);
            $responseData = app(ConfirmBookingsAction::class)->handle($parsedRequest);
        }
        if ($action === UpdateActionRequest::ACTION_NAME) {
            $parsedRequest = UpdateActionRequest::createFrom($request);
            $responseData = app(UpdateAction::class)->handle($parsedRequest);
        }
        if ($parsedRequest === null) {
            throw new BadRequestHttpException('Unknown Traveline request');
        }

        return $responseData;
    }

}
