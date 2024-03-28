<?php

namespace Pkg\Supplier\Traveline\Dto\Reservation;

class CustomerDto
{
    public function __construct(
        public readonly string $firstName,
        public readonly ?string $lastName,
        public readonly ?string $middleName,
        public readonly ?string $email = null,
        public readonly ?string $phone = null,
    ) {}
}
