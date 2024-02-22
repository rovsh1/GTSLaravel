<?php

namespace Pkg\Hotel\Administration\Application\Service\StatusRules;

use Module\Booking\Moderation\Domain\Booking\Service\StatusRules\AbstractTransitions;
use Module\Booking\Moderation\Domain\Booking\Service\StatusRules\StatusTransitionsInterface;
use Sdk\Booking\Enum\StatusEnum;

final class DefaultTransitions extends AbstractTransitions implements StatusTransitionsInterface
{
    protected function configure(): void
    {

        $this->addTransition(StatusEnum::WAITING_CONFIRMATION, StatusEnum::CONFIRMED);
        $this->addTransition(StatusEnum::WAITING_CONFIRMATION, StatusEnum::NOT_CONFIRMED);

        $this->addTransition(StatusEnum::WAITING_CANCELLATION, StatusEnum::CANCELLED_FEE);
        $this->addTransition(StatusEnum::WAITING_CANCELLATION, StatusEnum::CANCELLED_NO_FEE);
    }
}
