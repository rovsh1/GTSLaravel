<?php

declare(strict_types=1);

namespace Module\Booking\Invoicing\Domain\Service\Factory;

use Module\Booking\Invoicing\Domain\Service\Dto\Service\GuestDto;
use Module\Booking\Shared\Domain\Guest\Guest;
use Module\Booking\Shared\Domain\Guest\Repository\GuestRepositoryInterface;
use Sdk\Booking\ValueObject\GuestIdCollection;
use Sdk\Shared\Contracts\Adapter\CountryAdapterInterface;
use Sdk\Shared\Enum\GenderEnum;

class GuestDataFactory
{
    private array $countryNamesIndexedId;

    public function __construct(
        CountryAdapterInterface $countryAdapter,
        private readonly GuestRepositoryInterface $guestRepository
    ) {
        $countries = $countryAdapter->get();
        $this->countryNamesIndexedId = collect($countries)->keyBy('id')->map->name->all();
    }

    /**
     * @param GuestIdCollection $guestIds
     * @return GuestDto[]
     */
    public function build(GuestIdCollection $guestIds): array
    {
        if ($guestIds->count() === 0) {
            return [];
        }
        $guests = $this->guestRepository->get($guestIds);

        return collect($guests)->map(fn(Guest $guest) => new GuestDto(
            $guest->fullName(),
            $guest->gender() === GenderEnum::MALE ? 'Мужской' : 'Женский',
            $this->countryNamesIndexedId[$guest->countryId()]
        ))->all();
    }
}
