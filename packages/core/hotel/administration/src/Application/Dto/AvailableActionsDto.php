<?php

declare(strict_types=1);

namespace Pkg\Hotel\Administration\Application\Dto;

final class AvailableActionsDto
{
    public function __construct(
        public readonly array $statuses,
        public readonly bool $canEditExternalNumber,
        public readonly bool $canSetNoCheckIn,
        public readonly bool $canEditPenalty,
    ) {}
}
