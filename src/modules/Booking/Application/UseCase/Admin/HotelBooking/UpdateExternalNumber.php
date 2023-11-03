<?php

declare(strict_types=1);

namespace Module\Booking\Application\UseCase\Admin\HotelBooking;

use Module\Booking\Domain\Booking\Repository\Details\HotelBookingRepositoryInterface;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\ExternalNumber;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\ExternalNumberTypeEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateExternalNumber implements UseCaseInterface
{
    public function __construct(
        private readonly HotelBookingRepositoryInterface $repository,
    ) {}

    public function execute(int $bookingId, int $type, ?string $number): void
    {
        $typeEnum = ExternalNumberTypeEnum::from($type);
        $bookingDetails = $this->repository->findOrFail(new BookingId($bookingId));
        $externalNumber = new ExternalNumber($typeEnum, $number);
        $bookingDetails->setExternalNumber($externalNumber);
        $this->repository->store($bookingDetails);
    }
}