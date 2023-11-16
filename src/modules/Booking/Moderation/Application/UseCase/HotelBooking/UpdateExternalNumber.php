<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\HotelBooking;

use Module\Booking\Shared\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\ExternalNumber;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\ExternalNumberTypeEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateExternalNumber implements UseCaseInterface
{
    public function __construct(
        private readonly BookingUnitOfWorkInterface $bookingUnitOfWork,
    ) {
    }

    public function execute(int $bookingId, int $type, ?string $number): void
    {
        $typeEnum = ExternalNumberTypeEnum::from($type);
        $details = $this->bookingUnitOfWork->detailsRepository()->findOrFail(new BookingId($bookingId));
        if (!$details instanceof HotelBooking) {
            throw new \Exception('HotelBooking expected');
        }

        $details->setExternalNumber(new ExternalNumber($typeEnum, $number));

        $this->bookingUnitOfWork->commit();
    }
}
