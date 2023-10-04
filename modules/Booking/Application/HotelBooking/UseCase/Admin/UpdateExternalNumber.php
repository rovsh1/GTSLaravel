<?php

declare(strict_types=1);

namespace Module\Booking\Application\HotelBooking\UseCase\Admin;

use Module\Booking\Domain\HotelBooking\ValueObject\Details\AdditionalInfo;
use Module\Booking\Domain\HotelBooking\ValueObject\Details\AdditionalInfo\ExternalNumber;
use Module\Booking\Domain\HotelBooking\ValueObject\Details\AdditionalInfo\ExternalNumberTypeEnum;
use Module\Booking\Domain\Shared\Service\BookingUpdater;
use Module\Booking\Infrastructure\HotelBooking\Repository\BookingRepository;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateExternalNumber implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepository $repository,
        private readonly BookingUpdater $bookingUpdater,
    ) {}

    public function execute(int $id, int $type, ?string $number): void
    {
        $typeEnum = ExternalNumberTypeEnum::from($type);
        $booking = $this->repository->find($id);
        $externalNumber = new ExternalNumber($typeEnum, $number);
        if ($booking->additionalInfo() === null) {
            $additionalInfo = new AdditionalInfo(null);
            $booking->setAdditionalInfo($additionalInfo);
        }
        $booking->additionalInfo()->setExternalNumber($externalNumber);
        $this->bookingUpdater->store($booking);
    }
}
