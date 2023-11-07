<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\HotelBooking\Room;

use Module\Booking\Domain\Booking\Exception\HotelBooking\InvalidRoomResidency;
use Module\Booking\Domain\Booking\Exception\HotelBooking\NotFoundHotelRoomPrice;
use Module\Booking\Domain\Booking\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Domain\Booking\Service\HotelBooking\QuotaManager\Exception\ClosedRoomDateQuota;
use Module\Booking\Domain\Booking\Service\HotelBooking\QuotaManager\Exception\NotEnoughRoomDateQuota;
use Module\Booking\Domain\Booking\Service\HotelBooking\QuotaManager\Exception\NotFoundRoomDateQuota;
use Module\Booking\Domain\Booking\Service\HotelBooking\RoomUpdater\RoomUpdater;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomBookingId;
use Module\Booking\Moderation\Application\Dto\UpdateRoomDto;
use Module\Booking\Moderation\Application\Exception\BookingQuotaException;
use Module\Booking\Moderation\Application\Exception\InvalidRoomClientResidencyException;
use Module\Booking\Moderation\Application\Exception\NotFoundHotelRoomPriceException;
use Module\Booking\Moderation\Application\Factory\RoomUpdaterDataHelperFactory;
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
        $roomBooking = $this->roomBookingRepository->find(new RoomBookingId($request->roomBookingId));
        if ($roomBooking === null) {
            throw new EntityNotFoundException('Room booking not found');
        }
        try {
            $updateRoomDto = $this->dataHelperFactory->build($request, $roomBooking->guestIds(), $roomBooking->prices());
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
