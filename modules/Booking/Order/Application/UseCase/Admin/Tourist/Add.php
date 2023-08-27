<?php

declare(strict_types=1);

namespace Module\Booking\Order\Application\UseCase\Admin\Tourist;

use Module\Booking\Common\Domain\ValueObject\OrderId;
use Module\Booking\Order\Application\Request\AddTouristDto;
use Module\Booking\Order\Application\Response\TouristDto;
use Module\Booking\Order\Domain\Event\TouristCreated;
use Module\Booking\Order\Domain\Repository\TouristRepositoryInterface;
use Module\Shared\Domain\ValueObject\GenderEnum;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Add implements UseCaseInterface
{
    public function __construct(
        private readonly TouristRepositoryInterface $touristRepository,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {}

    public function execute(AddTouristDto $request): TouristDto
    {
        $tourist = $this->touristRepository->create(
            orderId: new OrderId($request->orderId),
            fullName: $request->fullName,
            gender: GenderEnum::from($request->gender),
            countryId: $request->countryId,
            isAdult: $request->isAdult,
            age: $request->age
        );

        $this->eventDispatcher->dispatch(new TouristCreated($tourist));

        return TouristDto::fromDomain($tourist);
    }
}
