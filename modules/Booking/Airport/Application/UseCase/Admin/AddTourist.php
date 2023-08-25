<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Application\UseCase\Admin;

use Module\Booking\Airport\Application\Request\AddTouristDto;
use Module\Booking\Airport\Domain\Repository\TouristRepositoryInterface;
use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\Common\Domain\ValueObject\TouristId;
use Module\Booking\Tourist\Application\Request\CreateTouristDto;
use Module\Booking\Tourist\Application\UseCase\CreateTourist;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class AddTourist implements UseCaseInterface
{
    public function __construct(
        private readonly TouristRepositoryInterface $touristRepository,
    ) {}

    public function execute(AddTouristDto $request): void
    {
        $touristDto = app(CreateTourist::class)->execute(
            new CreateTouristDto(
                fullName: $request->fullName,
                countryId: $request->countryId,
                gender: $request->gender,
                isAdult: $request->isAdult,
                age: $request->age,
            )
        );

        //@todo привязка туриста к заказу, может через событие?
        //@todo иначе придется в каждом домене (Hotel,Airport,Order) создавать отдельные команды: Add, Bind, Unbind
        $this->touristRepository->bind(
            new BookingId($request->bookingId),
            new TouristId($touristDto->id)
        );
    }
}
