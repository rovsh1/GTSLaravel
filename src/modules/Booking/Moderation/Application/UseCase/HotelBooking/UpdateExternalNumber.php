<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\HotelBooking;

use Module\Booking\Shared\Domain\Booking\Repository\Details\HotelBookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\ExternalNumber;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\ExternalNumberTypeEnum;
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
