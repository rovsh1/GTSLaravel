<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\HotelBooking\UseCase\Room;

use Module\Booking\Application\Admin\HotelBooking\Exception\BookingQuotaException;
use Module\Booking\Application\Admin\HotelBooking\Exception\InvalidRoomClientResidencyException;
use Module\Booking\Application\Admin\HotelBooking\Exception\NotFoundHotelRoomPriceException;
use Module\Booking\Application\Admin\HotelBooking\Factory\RoomUpdaterDataHelperFactory;
use Module\Booking\Application\Admin\HotelBooking\Request\UpdateRoomDto;
use Module\Booking\Domain\HotelBooking\Exception\InvalidRoomResidency;
use Module\Booking\Domain\HotelBooking\Exception\NotFoundHotelRoomPrice;
use Module\Booking\Domain\HotelBooking\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Domain\HotelBooking\Service\QuotaManager\Exception\ClosedRoomDateQuota;
use Module\Booking\Domain\HotelBooking\Service\QuotaManager\Exception\NotEnoughRoomDateQuota;
use Module\Booking\Domain\HotelBooking\Service\QuotaManager\Exception\NotFoundRoomDateQuota;
use Module\Booking\Domain\HotelBooking\Service\RoomUpdater\RoomUpdater;
use Module\Shared\Exception\ApplicationException;
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