<?php

namespace Module\Integration\Traveline\Application\Dto\Reservation\Room;

use Module\Integration\Traveline\Application\Dto\Reservation\FirstNameCast;
use Module\Integration\Traveline\Application\Dto\Reservation\LastNameCast;
use Module\Integration\Traveline\Application\Dto\Reservation\MiddleNameCast;
use Sdk\Module\Foundation\Support\Dto\Attributes\MapInputName;
use Sdk\Module\Foundation\Support\Dto\Attributes\WithCast;

class GuestDto
{
    public function __construct(
        #[MapInputName('fullName'), WithCast(FirstNameCast::class)]
        public readonly string $firstName,
        #[MapInputName('fullName'), WithCast(LastNameCast::class)]
        public readonly ?string $lastName,
        #[MapInputName('fullName'), WithCast(MiddleNameCast::class)]
        public readonly ?string $middleName,
        public readonly ?string $email = null,
        public readonly ?string $phone = null,
        public readonly bool $isChild = false,
    ) {}
}
