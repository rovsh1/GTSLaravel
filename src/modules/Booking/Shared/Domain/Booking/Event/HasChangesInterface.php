<?php

namespace Module\Booking\Shared\Domain\Booking\Event;

use Module\Booking\Moderation\Domain\Booking\Service\ChangeHistory\ChangesInterface;

interface HasChangesInterface
{
    public function changes(): ChangesInterface;
}