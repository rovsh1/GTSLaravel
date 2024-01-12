<?php

namespace Pkg\Supplier\Traveline\Dto\Reservation;

use Custom\Framework\Foundation\Support\Dto\Attributes\MapInputName;
use Custom\Framework\Foundation\Support\Dto\Attributes\WithCast;
use Custom\Framework\Foundation\Support\Dto\Dto;

class CustomerDto extends Dto
{
    public function __construct(
        public readonly string  $firstName,
        public readonly ?string $lastName,
        public readonly ?string $middleName,
        public readonly ?string $email = null,
        public readonly ?string $phone = null,
    ) {}
}
