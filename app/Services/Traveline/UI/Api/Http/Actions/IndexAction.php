<?php

namespace GTS\Services\Traveline\UI\Api\Http\Actions;

use GTS\Services\Traveline\UI\Api\Http\Requests\AbstractTravelineRequest;
use GTS\Services\Traveline\UI\Api\Http\Requests\ConfirmBookingsActionRequest;
use GTS\Services\Traveline\UI\Api\Http\Requests\GetBookingsActionRequest;
use GTS\Services\Traveline\UI\Api\Http\Requests\GetRoomsAndRatePlansActionRequest;
use GTS\Services\Traveline\UI\Api\Http\Requests\UpdateActionRequest;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class IndexAction
{
    const GetRoomsAndRatePlans = 'get-rooms-and-rate-plans';
    const GetBookings = 'get-bookings';
    const ConfirmBookings = 'confirm-bookings';
    const Update = 'update';

    public function handle(Request $request)
    {
        return match ($request->get('action')) {
            self::GetRoomsAndRatePlans => $this->action($request, GetRoomsAndRatePlansActionRequest::class, GetRoomsAndRatePlansAction::class),
            self::GetBookings => $this->action($request, GetBookingsActionRequest::class, GetBookingsAction::class),
            self::ConfirmBookings => $this->action($request, ConfirmBookingsActionRequest::class, ConfirmBookingsAction::class),
            self::Update => $this->action($request, UpdateActionRequest::class, UpdateAction::class),
            default => throw new BadRequestHttpException('Unknown Traveline request')
        };
    }

    /**
     * @param Request $request
     * @param class-string|AbstractTravelineRequest $requestClass
     * @param class-string $actionClass
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Throwable
     */
    private function action(Request $request, string $requestClass, string $actionClass)
    {
        $parsedRequest = $requestClass::createFrom($request);
        $request->validate($parsedRequest->rules());

        return app($actionClass)->handle($parsedRequest);
    }

}
