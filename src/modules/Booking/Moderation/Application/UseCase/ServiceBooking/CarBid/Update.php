<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\ServiceBooking\CarBid;

use Module\Booking\Moderation\Application\Dto\CarBidDataDto;
use Module\Booking\Moderation\Application\Exception\NotFoundServiceCancelConditionsException;
use Module\Booking\Moderation\Application\Exception\NotFoundServicePriceException as NotFoundApplicationException;
use Module\Booking\Moderation\Application\Exception\ServiceDateUndefinedException;
use Module\Booking\Moderation\Domain\Booking\Exception\NotFoundServiceCancelConditions;
use Module\Booking\Moderation\Domain\Booking\Service\TransferBooking\CarBidUpdater;
use Module\Booking\Shared\Domain\Booking\Exception\NotFoundTransferServicePrice;
use Module\Booking\Shared\Domain\Booking\Exception\ServiceDateUndefined;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
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
            throw new NotFoundApplicationException($e);
        } catch (ServiceDateUndefined $e) {
            throw new ServiceDateUndefinedException($e);
        } catch (NotFoundServiceCancelConditions $e) {
            throw new NotFoundServiceCancelConditionsException($e);
        }
    }
}
