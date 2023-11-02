<?php

declare(strict_types=1);

namespace Module\Booking\Application\UseCase\Admin\ServiceBooking\CarBid;

use Module\Booking\Application\Dto\CarBidDataDto;
use Module\Booking\Application\Exception\NotFoundServicePriceException as NotFoundApplicationException;
use Module\Booking\Application\Exception\ServiceDateUndefinedException;
use Module\Booking\Domain\Booking\Exception\NotFoundTransferServicePrice;
use Module\Booking\Domain\Booking\Exception\ServiceDateUndefined;
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
            throw new NotFoundApplicationException($e, NotFoundApplicationException::BOOKING_TRANSFER_SERVICE_PRICE_NOT_FOUND);
        } catch (ServiceDateUndefined $e) {
            throw new ServiceDateUndefinedException($e);
        }
    }
}
