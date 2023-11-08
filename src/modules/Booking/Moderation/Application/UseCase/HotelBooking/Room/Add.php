<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\HotelBooking\Room;

use Module\Booking\Moderation\Application\Dto\AddRoomDto;
use Module\Booking\Moderation\Application\Exception\BookingQuotaException;
use Module\Booking\Moderation\Application\Exception\InvalidRoomClientResidencyException;
use Module\Booking\Moderation\Application\Exception\NotFoundHotelRoomPriceException;
use Module\Booking\Moderation\Application\Factory\RoomUpdaterDataHelperFactory;
use Module\Booking\Moderation\Domain\Booking\Service\HotelBooking\RoomUpdater\RoomUpdater;
use Module\Booking\Shared\Domain\Booking\Exception\HotelBooking\InvalidRoomResidency;
use Module\Booking\Shared\Domain\Booking\Exception\HotelBooking\NotFoundHotelRoomPrice;
use Module\Booking\Shared\Domain\Booking\Service\HotelBooking\QuotaManager\Exception\ClosedRoomDateQuota;
use Module\Booking\Shared\Domain\Booking\Service\HotelBooking\QuotaManager\Exception\NotEnoughRoomDateQuota;
use Module\Booking\Shared\Domain\Booking\Service\HotelBooking\QuotaManager\Exception\NotFoundRoomDateQuota;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomPrices;
use Module\Booking\Shared\Domain\Shared\ValueObject\GuestIdCollection;
use Module\Shared\Exception\ApplicationException;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Add implements UseCaseInterface
{
    public function __construct(
        private readonly RoomUpdater $roomUpdater,
        private readonly RoomUpdaterDataHelperFactory $dataHelperFactory,
    ) {}

    public function execute(AddRoomDto $request): void
    {
        try {
            $addRoomDto = $this->dataHelperFactory->build($request, new GuestIdCollection(), RoomPrices::buildEmpty());
            $this->roomUpdater->add($addRoomDto);
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
