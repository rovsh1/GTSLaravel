<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\Order\Guest;

use Module\Booking\Moderation\Application\Dto\UpdateGuestDto;
use Module\Booking\Shared\Domain\Guest\Event\GuestModified;
use Module\Booking\Shared\Domain\Guest\Repository\GuestRepositoryInterface;
use Sdk\Booking\ValueObject\GuestId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;
use Sdk\Shared\Enum\GenderEnum;

class Update implements UseCaseInterface
{
    public function __construct(
        private readonly GuestRepositoryInterface $guestRepository,
        private readonly DomainEventDispatcherInterface $eventDispatcher
    ) {}

    public function execute(UpdateGuestDto $request): void
    {
        $guest = $this->guestRepository->find(new GuestId($request->guestId));
        if ($guest === null) {
            throw new EntityNotFoundException('Guest not found');
        }
        $guestBefore = clone $guest;
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

        $this->guestRepository->store($guest);
        $this->eventDispatcher->dispatch(new GuestModified($guest, $guestBefore));
    }
}
