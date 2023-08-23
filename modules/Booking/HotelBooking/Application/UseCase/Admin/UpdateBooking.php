<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\UseCase\Admin;

use Module\Booking\Common\Domain\Service\BookingUpdater;
use Module\Booking\HotelBooking\Application\Exception\BookingQuotaException;
use Module\Booking\HotelBooking\Application\Exception\NotFoundHotelCancelPeriod as NotFoundHotelCancelPeriodApplicationException;
use Module\Booking\HotelBooking\Application\Factory\CancelConditionsFactory;
use Module\Booking\HotelBooking\Application\Request\UpdateBookingDto;
use Module\Booking\HotelBooking\Domain\Adapter\HotelAdapterInterface;
use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Booking\HotelBooking\Domain\Exception\NotFoundHotelCancelPeriod;
use Module\Booking\HotelBooking\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\HotelBooking\Domain\Service\HotelValidator;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\Exception\ClosedRoomDateQuota;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\Exception\NotEnoughRoomDateQuota;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\Exception\NotFoundRoomDateQuota;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\QuotaManager;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\BookingPeriod;
use Module\Shared\Application\Exception\ApplicationException;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateBooking implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly BookingUpdater $bookingUpdater,
        private readonly HotelAdapterInterface $hotelAdapter,
        private readonly HotelValidator $hotelValidator,
        private readonly QuotaManager $quotaManager,
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
                throw new NotFoundHotelCancelPeriodApplicationException($e);
            }
            //@todo проверить доступность квот
            $cancelConditions = CancelConditionsFactory::fromDto($markupSettings->cancelPeriods, $request->period);
            $booking->setPeriod($periodFromRequest);
            $booking->setCancelConditions($cancelConditions);

            try {
                $this->quotaManager->process($booking);
            } catch (NotFoundRoomDateQuota $e) {
                throw new BookingQuotaException(ApplicationException::BOOKING_NOT_FOUND_ROOM_DATE_QUOTA, $e);
            } catch (ClosedRoomDateQuota $e) {
                throw new BookingQuotaException(ApplicationException::BOOKING_CLOSED_ROOM_DATE_QUOTA, $e);
            } catch (NotEnoughRoomDateQuota $e) {
                throw new BookingQuotaException(ApplicationException::BOOKING_NOT_ENOUGH_QUOTA, $e);
            }
        }

        if ($booking->note() !== $request->note) {
            $booking->setNote($request->note);
        }

        $this->bookingUpdater->store($booking);
    }
}
