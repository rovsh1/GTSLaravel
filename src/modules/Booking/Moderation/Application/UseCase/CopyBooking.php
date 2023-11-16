<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase;

use Carbon\CarbonPeriod;
use Module\Booking\Moderation\Application\Dto\ServiceBooking\BookingDto;
use Module\Booking\Moderation\Application\Factory\BookingDtoFactory;
use Module\Booking\Moderation\Application\Service\DetailsEditor\DetailsEditorFactory;
use Module\Booking\Shared\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Shared\Domain\Booking\Entity\ServiceDetailsInterface;
use Module\Booking\Shared\Domain\Booking\Factory\DetailsRepositoryFactory;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingPrices;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceId;
use Module\Booking\Shared\Domain\Shared\Adapter\AdministratorAdapterInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class CopyBooking implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly DetailsEditorFactory $detailsEditorFactory,
        private readonly DetailsRepositoryFactory $detailsRepositoryFactory,
        private readonly AdministratorAdapterInterface $administratorAdapter,
        private readonly BookingDtoFactory $bookingDtoFactory,
    ) {}

    public function execute(int $id): BookingDto
    {
        $booking = $this->repository->find(new BookingId($id));
        if ($booking === null) {
            throw new EntityNotFoundException('Booking not found');
        }
        $newBooking = $this->repository->create(
            orderId: $booking->orderId(),
            creatorId: $booking->context()->creatorId(),
            prices: BookingPrices::createEmpty(
                $booking->prices()->supplierPrice()->currency(),
                $booking->prices()->clientPrice()->currency()
            ),
            cancelConditions: $booking->cancelConditions(),
            serviceType: $booking->serviceType(),
            note: $booking->note(),
        );
        /** @var HotelBooking|ServiceDetailsInterface $details */
        $details = $this->detailsRepositoryFactory->build($booking)->find($booking->id());
        $editor = $this->detailsEditorFactory->build($newBooking);
        if ($details instanceof HotelBooking) {
            $editor->create($newBooking->id(), new ServiceId($details->hotelInfo()->id()), [
                'period' => new CarbonPeriod($details->bookingPeriod()->dateFrom(), $details->bookingPeriod()->dateTo()),
                'quota_processing_method' => $details->quotaProcessingMethod()->value,
            ]);
        } else {
            $editor->create($newBooking->id(), new ServiceId($details->serviceInfo()->id()), []);
        }

        $administrator = $this->administratorAdapter->getBookingAdministrator($booking->id());
        if ($administrator !== null) {
            $this->administratorAdapter->setBookingAdministrator($newBooking->id(), $administrator->id);
        }

        return $this->bookingDtoFactory->createFromEntity($newBooking);
    }
}
