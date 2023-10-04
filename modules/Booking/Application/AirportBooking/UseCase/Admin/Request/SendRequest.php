<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Application\UseCase\Admin\Request;

use Module\Booking\Airport\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Application\Shared\Support\UseCase\Admin\Request\SendRequest as Base;
use Module\Booking\Domain\Shared\Service\BookingUpdater;
use Module\Booking\Domain\Shared\Service\RequestCreator;

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
