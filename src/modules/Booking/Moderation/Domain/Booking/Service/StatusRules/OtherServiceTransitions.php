<?php

namespace Module\Booking\Moderation\Domain\Booking\Service\StatusRules;

use Sdk\Booking\Enum\StatusEnum;

final class OtherServiceTransitions extends AbstractTransitions implements StatusTransitionsInterface
{
    protected function configure(): void
    {
        $this->addTransition(StatusEnum::CREATED, StatusEnum::PROCESSING);
        $this->addTransition(StatusEnum::CREATED, StatusEnum::CANCELLED);
        $this->addTransition(StatusEnum::PROCESSING, StatusEnum::CONFIRMED);
        $this->addTransition(StatusEnum::CONFIRMED, StatusEnum::PROCESSING);
        $this->addTransition(StatusEnum::CONFIRMED, StatusEnum::CANCELLED);
    }
}
