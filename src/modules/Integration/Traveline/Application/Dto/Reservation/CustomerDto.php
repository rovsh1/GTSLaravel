<?php

namespace Module\Integration\Traveline\Application\Dto\Reservation;

use Sdk\Module\Foundation\Support\Dto\Attributes\MapInputName;
use Sdk\Module\Foundation\Support\Dto\Attributes\WithCast;

class CustomerDto
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
    ) {}
}
