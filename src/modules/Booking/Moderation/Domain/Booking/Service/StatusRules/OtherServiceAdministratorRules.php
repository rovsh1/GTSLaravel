<?php

namespace Module\Booking\Moderation\Domain\Booking\Service\StatusRules;

use Module\Booking\Requesting\Domain\BookingRequest\Service\RequestRules;
use Module\Shared\Enum\Booking\BookingStatusEnum;

class OtherServiceAdministratorRules extends AdministratorRules
{
    public function __construct(
        RequestRules $requestRules
    ) {
        parent::__construct($requestRules);
        $this->transitions = [];
        $this->addTransition(BookingStatusEnum::CONFIRMED, BookingStatusEnum::CANCELLED);
    }
}
