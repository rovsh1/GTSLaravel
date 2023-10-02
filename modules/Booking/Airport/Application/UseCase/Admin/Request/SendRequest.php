<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Application\UseCase\Admin\Request;

use Module\Booking\Airport\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Common\Application\Support\UseCase\Admin\Request\SendRequest as Base;
use Module\Booking\Common\Domain\Service\BookingUpdater;
use Module\Booking\Common\Domain\Service\RequestCreator;

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
