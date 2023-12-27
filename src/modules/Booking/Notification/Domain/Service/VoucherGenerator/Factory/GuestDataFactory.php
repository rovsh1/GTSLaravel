<?php

declare(strict_types=1);

namespace Module\Booking\Notification\Domain\Service\VoucherGenerator\Factory;

use Module\Booking\Notification\Domain\Service\VoucherGenerator\Dto\Service\GuestDto;
use Module\Booking\Shared\Domain\Guest\Guest;
use Module\Booking\Shared\Domain\Guest\Repository\GuestRepositoryInterface;
use Sdk\Booking\ValueObject\GuestIdCollection;
use Sdk\Shared\Contracts\Adapter\CountryAdapterInterface;
use Sdk\Shared\Enum\GenderEnum;

class GuestDataFactory
{
    public function __construct(
        private readonly CountryAdapterInterface $countryAdapter,
        private readonly GuestRepositoryInterface $guestRepository
    ) {}

    /**
     * @param GuestIdCollection $guestIds
     * @return GuestDto[]
     */
    public function build(GuestIdCollection $guestIds): array
    {
        if ($guestIds->count() === 0) {
            return [];
        }
        $countries = $this->countryAdapter->get();
        $countryNamesIndexedId = collect($countries)->keyBy('id')->map->name->all();

        $guests = $this->guestRepository->get($guestIds);

        return collect($guests)->map(fn(Guest $guest) => new GuestDto(
            $guest->fullName(),
            __($guest->gender() === GenderEnum::MALE ? 'Мужской' : 'Женский'),
            $countryNamesIndexedId[$guest->countryId()]
        ))->all();
    }
}
