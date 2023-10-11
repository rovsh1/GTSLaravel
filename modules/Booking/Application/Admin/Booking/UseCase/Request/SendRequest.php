<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\Booking\UseCase\Request;

use Module\Booking\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\BookingRequest\Service\RequestCreator;
use Module\Booking\Domain\Shared\Service\BookingUpdater;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SendRequest implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly RequestCreator $requestCreator,
        private readonly BookingUpdater $bookingUpdater
    ) {}

    public function execute(int $id): void
    {
        $booking = $this->repository->find(new BookingId($id));
        //@todo новая генерация реквестов
//        $booking->generateRequest(new RequestRules(), $this->requestCreator);
        $this->bookingUpdater->store($booking);
    }
}
