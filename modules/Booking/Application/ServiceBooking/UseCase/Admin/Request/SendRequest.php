<?php

declare(strict_types=1);

namespace Module\Booking\Application\ServiceBooking\UseCase\Admin\Request;

use Module\Booking\Application\Shared\Support\UseCase\Admin\Request\SendRequest as Base;
use Module\Booking\Domain\Shared\Service\BookingUpdater;
use Module\Booking\Domain\BookingRequest\Service\RequestCreator;
use Module\Booking\Domain\ServiceBooking\Repository\BookingRepositoryInterface;

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
