<?php

declare(strict_types=1);

namespace Module\Booking\Tourist\Application\UseCase;

use Module\Booking\Tourist\Application\Request\CreateTouristDto;
use Module\Booking\Tourist\Application\Response\TouristDto;
use Module\Booking\Tourist\Domain\Event\TouristCreated;
use Module\Booking\Tourist\Domain\Repository\TouristRepositoryInterface;
use Module\Shared\Domain\ValueObject\GenderEnum;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class CreateTourist implements UseCaseInterface
{
    public function __construct(
        private readonly TouristRepositoryInterface $repository,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {}

    public function execute(CreateTouristDto $request): TouristDto
    {
        $tourist = $this->repository->create(
            $request->fullName,
            $request->countryId,
            GenderEnum::from($request->gender),
            $request->isAdult,
            $request->age
        );

        $this->eventDispatcher->dispatch(new TouristCreated($tourist));

        return TouristDto::fromDomain($tourist);
    }
}
