<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\UseCase\Admin\Room;

use Module\Booking\HotelBooking\Application\Exception\BookingQuotaException;
use Module\Booking\HotelBooking\Application\Exception\InvalidRoomClientResidencyException;
use Module\Booking\HotelBooking\Application\Exception\NotFoundHotelRoomPriceException;
use Module\Booking\HotelBooking\Application\Factory\RoomUpdaterDataHelperFactory;
use Module\Booking\HotelBooking\Application\Request\UpdateRoomDto;
use Module\Booking\HotelBooking\Domain\Exception\InvalidRoomResidency;
use Module\Booking\HotelBooking\Domain\Exception\NotFoundHotelRoomPrice;
use Module\Booking\HotelBooking\Domain\Repository\RoomBookingRepositoryInterface;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\Exception\ClosedRoomDateQuota;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\Exception\NotEnoughRoomDateQuota;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\Exception\NotFoundRoomDateQuota;
use Module\Booking\HotelBooking\Domain\Service\RoomUpdater\RoomUpdater;
use Module\Shared\Application\Exception\ApplicationException;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class Update implements UseCaseInterface
{
    public function __construct(
        private readonly RoomUpdater $roomUpdater,
        private readonly RoomUpdaterDataHelperFactory $dataHelperFactory,
        private readonly RoomBookingRepositoryInterface $roomBookingRepository
    ) {}

    public function execute(UpdateRoomDto $request): void
    {
        $roomBooking = $this->roomBookingRepository->find($request->roomBookingId);
        if ($roomBooking === null) {
            throw new EntityNotFoundException('Room booking not found');
        }
        try {
            $updateRoomDto = $this->dataHelperFactory->build($request, $roomBooking->guestIds(), $roomBooking->price());
            $this->roomUpdater->update($roomBooking->id(), $updateRoomDto);
        } catch (InvalidRoomResidency $e) {
            throw new InvalidRoomClientResidencyException($e);
        } catch (NotFoundRoomDateQuota $e) {
            throw new BookingQuotaException(ApplicationException::BOOKING_NOT_FOUND_ROOM_DATE_QUOTA, $e);
        } catch (ClosedRoomDateQuota $e) {
            throw new BookingQuotaException(ApplicationException::BOOKING_CLOSED_ROOM_DATE_QUOTA, $e);
        } catch (NotEnoughRoomDateQuota $e) {
            throw new BookingQuotaException(ApplicationException::BOOKING_NOT_ENOUGH_QUOTA, $e);
        } catch (NotFoundHotelRoomPrice $e) {
            throw new NotFoundHotelRoomPriceException($e);
        }
    }
}
