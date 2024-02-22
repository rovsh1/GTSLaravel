<?php

namespace Pkg\Booking\Requesting\Domain\Event;

use Pkg\Booking\Requesting\Domain\Entity\BookingRequest;
use Pkg\Booking\Requesting\Domain\ValueObject\RequestId;
use Sdk\Booking\Contracts\Event\BookingEventInterface;
use Sdk\Module\Contracts\Event\HasIntegrationEventInterface;

/**
 * @property BookingRequest $request
 */
interface BookingRequestEventInterface extends BookingEventInterface, HasIntegrationEventInterface
{
    public function requestId(): RequestId;
}
