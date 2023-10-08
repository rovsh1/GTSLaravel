<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\HotelBooking\UseCase\Room;

use Module\Booking\Application\Admin\HotelBooking\Exception\BookingQuotaException;
use Module\Booking\Application\Admin\HotelBooking\Exception\InvalidRoomClientResidencyException;
use Module\Booking\Application\Admin\HotelBooking\Exception\NotFoundHotelRoomPriceException;
use Module\Booking\Application\Admin\HotelBooking\Factory\RoomUpdaterDataHelperFactory;
use Module\Booking\Application\Admin\HotelBooking\Request\AddRoomDto;
use Module\Booking\Domain\HotelBooking\Exception\InvalidRoomResidency;
use Module\Booking\Domain\HotelBooking\Exception\NotFoundHotelRoomPrice;
use Module\Booking\Domain\HotelBooking\Service\QuotaManager\Exception\ClosedRoomDateQuota;
use Module\Booking\Domain\HotelBooking\Service\QuotaManager\Exception\NotEnoughRoomDateQuota;
use Module\Booking\Domain\HotelBooking\Service\QuotaManager\Exception\NotFoundRoomDateQuota;
use Module\Booking\Domain\HotelBooking\Service\RoomUpdater\RoomUpdater;
use Module\Booking\Domain\HotelBooking\ValueObject\RoomPrice;
use Module\Booking\Domain\Shared\ValueObject\GuestIdCollection;
use Module\Shared\Application\Exception\ApplicationException;
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
            $addRoomDto = $this->dataHelperFactory->build($request, new GuestIdCollection(), RoomPrice::buildEmpty());
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
