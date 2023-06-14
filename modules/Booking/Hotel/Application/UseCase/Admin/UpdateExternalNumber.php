<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\UseCase\Admin;

use Module\Booking\Hotel\Domain\ValueObject\Details\AdditionalInfo;
use Module\Booking\Hotel\Domain\ValueObject\Details\AdditionalInfo\ExternalNumber;
use Module\Booking\Hotel\Domain\ValueObject\Details\AdditionalInfo\ExternalNumberTypeEnum;
use Module\Booking\Hotel\Infrastructure\Repository\BookingRepository;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateExternalNumber implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepository $repository
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
        $this->repository->store($booking);
    }
}
