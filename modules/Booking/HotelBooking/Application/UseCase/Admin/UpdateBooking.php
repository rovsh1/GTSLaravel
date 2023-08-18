<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\UseCase\Admin;

use Module\Booking\Common\Domain\Service\BookingUpdater;
use Module\Booking\HotelBooking\Application\Exception\NotFoundHotelCancelPeriod as ApplicationException;
use Module\Booking\HotelBooking\Application\Factory\CancelConditionsFactory;
use Module\Booking\HotelBooking\Application\Request\UpdateBookingDto;
use Module\Booking\HotelBooking\Domain\Adapter\HotelAdapterInterface;
use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Booking\HotelBooking\Domain\Exception\NotFoundHotelCancelPeriod;
use Module\Booking\HotelBooking\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\HotelBooking\Domain\Service\HotelValidator;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\BookingPeriod;
use Module\Shared\Enum\Booking\QuotaProcessingMethodEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateBooking implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly BookingUpdater $bookingUpdater,
        private readonly HotelAdapterInterface $hotelAdapter,
        private readonly HotelValidator $hotelValidator
    ) {}

    public function execute(UpdateBookingDto $request): void
    {
        /** @var Booking $booking */
        $booking = $this->repository->find($request->id);
        $periodFromRequest = BookingPeriod::fromCarbon($request->period);
        if (!$booking->period()->isEqual($periodFromRequest)) {
            $markupSettings = $this->hotelAdapter->getMarkupSettings($booking->hotelInfo()->id());
            try {
                $this->hotelValidator->validateById($booking->hotelInfo()->id(), $request->period);
            } catch (NotFoundHotelCancelPeriod $e) {
                throw new ApplicationException($e);
            }
            $cancelConditions = CancelConditionsFactory::fromDto($markupSettings->cancelPeriods, $request->period);
            $booking->setPeriod($periodFromRequest);
            $booking->setCancelConditions($cancelConditions);
        }

        if ($booking->note() !== $request->note) {
            $booking->setNote($request->note);
        }

        $this->bookingUpdater->store($booking);
    }
}
