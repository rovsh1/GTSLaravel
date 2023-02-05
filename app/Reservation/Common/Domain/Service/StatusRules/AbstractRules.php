<?php

namespace GTS\Reservation\Common\Domain\Service\StatusRules;

use GTS\Reservation\Domain\ValueObject\StatusEnum;

class AbstractRules
{
    protected array $transitions = [];

    protected function addTransition(StatusEnum $fromStatus, StatusEnum $toStatus): void
    {
        if (!isset($this->transitions[$fromStatus->value]))
            $this->transitions[$fromStatus->value] = [];

        $this->transitions[$fromStatus->value][] = $toStatus->value;
    }

    public function canTransit(StatusEnum $fromStatus, StatusEnum $toStatus): bool
    {
        if (!isset($this->transitions[$fromStatus->value]))
            return false;

        return in_array($toStatus->value, $this->transitions[$fromStatus->value]);
    }
}
