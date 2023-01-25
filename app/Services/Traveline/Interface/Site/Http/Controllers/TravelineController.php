<?php

namespace GTS\Services\Traveline\Interface\Site\Http\Controllers;

use GTS\Services\Traveline\Interface\Site\Http\Requests\ConfirmBookingsActionRequest;
use GTS\Services\Traveline\Interface\Site\Http\Requests\GetBookingsActionRequest;
use GTS\Services\Traveline\Interface\Site\Http\Requests\GetRoomsAndRatePlansActionRequest;
use GTS\Services\Traveline\Interface\Site\Http\Requests\UpdateActionRequest;
use GTS\Shared\Interface\Common\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TravelineController extends Controller
{

    public function index(Request $request)
    {
        $action = $request->get('action');
        $responseData = [];
        $parsedRequest = null;
        if ($action === GetRoomsAndRatePlansActionRequest::ACTION_NAME) {
            $parsedRequest = GetRoomsAndRatePlansActionRequest::createFrom($request);
            //@todo run command
        }
        if ($action === GetBookingsActionRequest::ACTION_NAME) {
            $parsedRequest = GetBookingsActionRequest::createFrom($request);
            //@todo run command
        }
        if ($action === ConfirmBookingsActionRequest::ACTION_NAME) {
            $parsedRequest = ConfirmBookingsActionRequest::createFrom($request);
            //@todo run command
        }
        if ($action === UpdateActionRequest::ACTION_NAME) {
            $parsedRequest = UpdateActionRequest::createFrom($request);
            //@todo run command
        }
        if ($parsedRequest === null) {
            throw new BadRequestHttpException('Unknown Traveline request');
        }

        return response()->json($responseData);
    }

}
