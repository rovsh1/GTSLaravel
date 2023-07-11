<?php

namespace Module\Integration\Traveline\Application\Dto\Reservation\Room;

use Custom\Framework\Foundation\Support\Dto\Dto;

class GuestDto extends Dto
{
    public function __construct(
        public readonly string $firstName,
        public readonly ?string $lastName,
        public readonly ?string $middleName,
        public readonly ?string $email = null,
        public readonly ?string $phone = null,
        public readonly bool $isChild = false,
    ) {}
}
