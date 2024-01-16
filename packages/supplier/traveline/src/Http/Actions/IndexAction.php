<?php

namespace Pkg\Supplier\Traveline\Http\Actions;

use Illuminate\Http\Request;
use Pkg\Supplier\Traveline\Http\Request\ConfirmBookingsActionRequest;
use Pkg\Supplier\Traveline\Http\Request\GetReservationsActionRequest;
use Pkg\Supplier\Traveline\Http\Request\GetRoomsAndRatePlansActionRequest;
use Pkg\Supplier\Traveline\Http\Request\UpdateActionRequest;
use Sdk\Module\Contracts\Support\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class IndexAction
{
    public function __construct(
        private readonly ContainerInterface $container
    ) {}

    public function handle(Request $request)
    {
        $actionName = ActionNameEnum::tryFrom($request->get('action'));
        if ($actionName === null) {
            throw new BadRequestHttpException('Unknown Traveline request');
        }
        $parsedRequest = $this->getRequest($actionName, $request);
        $request->validate($parsedRequest->rules());

        return $this->container->make($this->getActionClass($actionName))->handle($parsedRequest);
    }

    /**
     * @return GetRoomsAndRatePlansActionRequest|GetReservationsActionRequest|ConfirmBookingsActionRequest|UpdateActionRequest
     */
    private function getRequest(ActionNameEnum $actionName, Request $request)
    {
        return match ($actionName) {
            ActionNameEnum::GetRoomsAndRatePlans => GetRoomsAndRatePlansActionRequest::createFrom($request),
            ActionNameEnum::GetBookings => GetReservationsActionRequest::createFrom($request),
            ActionNameEnum::ConfirmBookings => ConfirmBookingsActionRequest::createFrom($request),
            ActionNameEnum::Update => UpdateActionRequest::createFrom($request),
        };
    }

    private function getActionClass(ActionNameEnum $actionName): string
    {
        return match ($actionName) {
            ActionNameEnum::GetRoomsAndRatePlans => GetRoomsAndRatePlansAction::class,
            ActionNameEnum::GetBookings => GetReservationsAction::class,
            ActionNameEnum::ConfirmBookings => ConfirmBookingsAction::class,
            ActionNameEnum::Update => UpdateAction::class,
        };
    }
}
