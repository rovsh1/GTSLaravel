<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\HotelBooking\UseCase;

use Module\Booking\Application\Admin\HotelBooking\Exception\BookingQuotaException;
use Module\Booking\Application\Admin\HotelBooking\Exception\NotFoundHotelCancelPeriod as NotFoundHotelCancelPeriodApplicationException;
use Module\Booking\Application\Admin\HotelBooking\Factory\CancelConditionsFactory;
use Module\Booking\Application\Admin\HotelBooking\Request\UpdateBookingDto;
use Module\Booking\Deprecated\HotelBooking\Adapter\HotelAdapterInterface;
use Module\Booking\Deprecated\HotelBooking\Exception\NotFoundHotelCancelPeriod;
use Module\Booking\Deprecated\HotelBooking\HotelBooking;
use Module\Booking\Deprecated\HotelBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Deprecated\HotelBooking\Service\HotelValidator;
use Module\Booking\Deprecated\HotelBooking\Service\QuotaManager\Exception\ClosedRoomDateQuota;
use Module\Booking\Deprecated\HotelBooking\Service\QuotaManager\Exception\NotEnoughRoomDateQuota;
use Module\Booking\Deprecated\HotelBooking\Service\QuotaManager\Exception\NotFoundRoomDateQuota;
use Module\Booking\Deprecated\HotelBooking\Service\QuotaManager\QuotaManager;
use Module\Booking\Domain\Booking\ValueObject\BookingPeriod;
use Module\Booking\Domain\Shared\Service\BookingUpdater;
use Module\Shared\Exception\ApplicationException;
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
        /** @var HotelBooking $booking */
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
