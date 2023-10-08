<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\UseCase\Request;

use Module\Booking\Application\Admin\Shared\Support\UseCase\Request\SendRequest as Base;
use Module\Booking\Domain\BookingRequest\Service\RequestCreator;
use Module\Booking\Domain\ServiceBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Shared\Service\BookingUpdater;

class SendRequest extends Base
{
    public function __construct(
        BookingRepositoryInterface $repository,
        RequestCreator $requestCreator,
        BookingUpdater $bookingUpdater
    ) {
        parent::__construct($repository, $requestCreator, $bookingUpdater);
    }
}
