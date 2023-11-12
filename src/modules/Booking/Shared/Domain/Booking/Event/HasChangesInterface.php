<?php

namespace Module\Booking\Shared\Domain\Booking\Event;

use Module\Booking\EventSourcing\Domain\Service\ChangesInterface;

interface HasChangesInterface
{
    public function changes(): ChangesInterface;
}