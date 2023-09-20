<?php

declare(strict_types=1);

namespace Module\Booking\Order\Application\UseCase\Admin\Guest;

use Module\Booking\Common\Domain\ValueObject\OrderId;
use Module\Booking\Order\Application\Request\AddGuestDto;
use Module\Booking\Order\Application\Response\GuestDto;
use Module\Booking\Order\Domain\Event\GuestCreated;
use Module\Booking\Order\Domain\Repository\GuestRepositoryInterface;
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
            gender: GenderEnum::from($request->gender),
            countryId: $request->countryId,
            isAdult: $request->isAdult,
            age: $request->age
        );

        $this->eventDispatcher->dispatch(new GuestCreated($guest->id()));

        return GuestDto::fromDomain($guest);
    }
}
