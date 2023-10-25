<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\UseCase\CarBid;

use Module\Booking\Application\Admin\ServiceBooking\Request\CarBidDataDto;
use Module\Booking\Application\AirportBooking\Exception\NotFoundServicePriceException as ApplicationException;
use Module\Booking\Domain\Booking\Exception\NotFoundTransferServicePrice;
use Module\Booking\Domain\Booking\Service\TransferBooking\CarBidUpdater;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Update implements UseCaseInterface
{
    public function __construct(
        private readonly CarBidUpdater $carBidUpdater,
    ) {}

    public function execute(int $bookingId, string $carBidId, CarBidDataDto $carData): void
    {
        try {
            $this->carBidUpdater->update(new BookingId($bookingId), $carBidId, $carData);
        } catch (NotFoundTransferServicePrice $e) {
            throw new ApplicationException($e, ApplicationException::BOOKING_TRANSFER_SERVICE_PRICE_NOT_FOUND);
        }
    }
}
