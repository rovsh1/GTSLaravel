<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\Order\Guest;

use Module\Booking\Moderation\Application\Dto\UpdateGuestDto;
use Module\Booking\Shared\Domain\Guest\Repository\GuestRepositoryInterface;
use Module\Booking\Shared\Domain\Guest\ValueObject\GuestId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;
use Sdk\Shared\Enum\GenderEnum;

class Update implements UseCaseInterface
{
    public function __construct(
        private readonly GuestRepositoryInterface $guestRepository,
    ) {}

    public function execute(UpdateGuestDto $request): bool
    {
        $guest = $this->guestRepository->find(new GuestId($request->guestId));
        if ($guest === null) {
            throw new EntityNotFoundException('Guest not found');
        }
        if ($guest->fullName() !== $request->fullName) {
            $guest->setFullName($request->fullName);
        }
        if ($guest->countryId() !== $request->countryId) {
            $guest->setCountryId($request->countryId);
        }
        $newGender = GenderEnum::from($request->gender);
        if ($guest->gender() !== $newGender) {
            $guest->setGender($newGender);
        }
        if ($guest->isAdult() !== $request->isAdult) {
            $guest->setIsAdult($request->isAdult);
        }
        if ($guest->age() !== $request->age) {
            $guest->setAge($request->age);
        }

        return $this->guestRepository->store($guest);
    }
}
