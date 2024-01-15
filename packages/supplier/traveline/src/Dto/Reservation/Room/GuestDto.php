<?php

namespace Pkg\Supplier\Traveline\Dto\Reservation\Room;

class GuestDto
{
    public function __construct(
        public readonly string $firstName,
        public readonly ?string $lastName,
        public readonly ?string $middleName,
        public readonly ?string $email = null,
        public readonly ?string $phone = null,
        public readonly bool $isChild = false,
    ) {}

    public static function from(): static
    {
        return new static();
    }

    public static function collection(array $guests): array
    {
        return [];
//        return array_map(fn())
    }
}
