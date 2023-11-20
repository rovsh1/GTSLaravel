<?php

declare(strict_types=1);

namespace Module\Booking\Pricing\Application\UseCase\HotelBooking;

use Module\Booking\Pricing\Domain\Booking\Service\RecalculatePriceService;
use Module\Booking\Shared\Domain\Booking\Repository\AccommodationRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\AccommodationId;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomPriceItem;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomPrices;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SetRoomManualPrice implements UseCaseInterface
{
    public function __construct(
        private readonly RecalculatePriceService $recalculatePriceService,
        private readonly AccommodationRepositoryInterface $accommodationRepository,
    ) {
    }

    public function execute(
        int $bookingId,
        int $accommodationId,
        float|null $supplierDayPrice,
        float|null $clientDayPrice,
    ): void {
        $accommodation = $this->accommodationRepository->findOrFail(new AccommodationId($accommodationId));
        $accommodation->updatePrices(
            new RoomPrices(
                supplierPrice: new RoomPriceItem(
                    $accommodation->prices()->supplierPrice()->dayParts(),
                    $supplierDayPrice,
                ),
                clientPrice: new RoomPriceItem(
                    $accommodation->prices()->clientPrice()->dayParts(),
                    $clientDayPrice,
                ),
            )
        );

        $this->recalculatePriceService->recalculate(new BookingId($bookingId));
    }
}
