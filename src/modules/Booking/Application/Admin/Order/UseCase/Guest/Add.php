<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\Order\UseCase\Guest;

use Module\Booking\Application\Admin\Order\Request\AddGuestDto;
use Module\Booking\Application\Admin\Order\Response\GuestDto;
use Module\Booking\Domain\Order\Event\GuestCreated;
use Module\Booking\Domain\Order\Repository\GuestRepositoryInterface;
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
