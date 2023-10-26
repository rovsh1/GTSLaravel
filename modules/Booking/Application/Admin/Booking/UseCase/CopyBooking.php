<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\Booking\UseCase;

use Carbon\CarbonPeriod;
use Module\Booking\Application\Admin\ServiceBooking\Service\DetailsEditor\DetailsEditorFactory;
use Module\Booking\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Domain\Booking\Factory\DetailsRepositoryFactory;
use Module\Booking\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\BookingPrices;
use Module\Booking\Domain\Booking\ValueObject\ServiceId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class CopyBooking implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly DetailsEditorFactory $detailsEditorFactory,
        private readonly DetailsRepositoryFactory $detailsRepositoryFactory
    ) {}

    public function execute(int $id): int
    {
        $booking = $this->repository->find(new BookingId($id));
        if ($booking === null) {
            throw new EntityNotFoundException('Booking not found');
        }
        $newBooking = $this->repository->create(
            orderId: $booking->orderId(),
            creatorId: $booking->creatorId(),
            prices: BookingPrices::createEmpty(
                $booking->prices()->supplierPrice()->currency(),
                $booking->prices()->clientPrice()->currency()
            ),
            cancelConditions: $booking->cancelConditions(),
            serviceType: $booking->serviceType(),
            note: $booking->note(),
        );
        /** @var HotelBooking $details */
        $details = $this->detailsRepositoryFactory->build($booking)->find($booking->id());

        $editor = $this->detailsEditorFactory->build($newBooking);
        //@todo поправить, чтобы работало для всех видов броней
        $editor->create($newBooking->id(), new ServiceId($details->hotelInfo()->id()), [
            'period' => new CarbonPeriod($details->bookingPeriod()->dateFrom(), $details->bookingPeriod()->dateTo()),
            'quota_processing_method' => $details->quotaProcessingMethod()->value,
        ]);

        return $newBooking->id()->value();
    }
}
