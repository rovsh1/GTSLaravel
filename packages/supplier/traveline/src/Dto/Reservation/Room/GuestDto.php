<?php

namespace Pkg\Supplier\Traveline\Dto\Reservation\Room;

use Module\Booking\Moderation\Application\Dto\GuestDto as BookingGuestDto;

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

    public static function from(BookingGuestDto $guest): static
    {
        [$name, $lastName, $middleName] = self::getFullNameParts($guest->fullName);

        return new static($name, $lastName, $middleName, isChild: !$guest->isAdult);
    }

    /**
     * @param BookingGuestDto[] $guests
     * @return GuestDto[]
     */
    public static function collection(array $guests): array
    {
        return array_map(fn(BookingGuestDto $guest) => self::from($guest), $guests);
    }

    private static function getFullNameParts(string $fullName): array
    {
        $nameParts = explode(' ', $fullName);
        $middleName = null;

        if (count($nameParts) === 3) {
            $middleName = $nameParts[2];
        }

        return [
            $nameParts[0],
            $nameParts[1] ?? null,
            $middleName
        ];
    }
}
