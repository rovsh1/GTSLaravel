<?php

declare(strict_types=1);

namespace Module\Booking\Application\UseCase\Admin\Order\Guest;

use Module\Booking\Application\Dto\AddGuestDto;
use Module\Booking\Application\Dto\GuestDto;
use Module\Booking\Domain\Guest\Event\GuestCreated;
use Module\Booking\Domain\Guest\Repository\GuestRepositoryInterface;
use Module\Booking\Domain\Order\ValueObject\OrderId;
use Module\Shared\Enum\GenderEnum;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Add implements UseCaseInterface
{
    public function __construct(
        private readonly GuestRepositoryInterface $guestRepository,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {}

    public function execute(AddGuestDto $request): GuestDto
    {
        $guest = $this->guestRepository->create(
            orderId: new OrderId($request->orderId),
            fullName: $request->fullName,
            countryId: $request->countryId,
            gender: GenderEnum::from($request->gender),
            isAdult: $request->isAdult,
            age: $request->age
        );

        $this->eventDispatcher->dispatch(new GuestCreated($guest->id()));

        return GuestDto::fromDomain($guest);
    }
}
