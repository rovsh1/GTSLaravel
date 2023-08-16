<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\Service;

use Module\Booking\Common\Domain\Adapter\ClientAdapterInterface;
use Module\Booking\Common\Domain\ValueObject\OrderId;
use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Booking\HotelBooking\Domain\Exception\InvalidRoomResidency;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\GuestCollection;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingDetails;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingStatusEnum;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomInfo;
use Module\Booking\HotelBooking\Domain\ValueObject\RoomPrice;
use Module\Booking\Order\Domain\Repository\OrderRepositoryInterface;
use Module\Shared\Enum\Client\ResidencyEnum;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class RoomAvailabilityValidator
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly ClientAdapterInterface $clientAdapter,
    ) {}

    public function validateRoomData(
        Booking $booking,
        RoomBookingStatusEnum $status,
        RoomInfo $roomInfo,
        GuestCollection $guests,
        RoomBookingDetails $details,
        RoomPrice $price
    ): void {
        $clientResidency = $this->getClientResidency($booking->orderId());
        if ($details->isResident() && !in_array($clientResidency, [ResidencyEnum::RESIDENT, ResidencyEnum::ALL])) {
            throw new InvalidRoomResidency('Client doesn\'t support resident prices');
        }

        if (!$details->isResident() && !in_array($clientResidency, [ResidencyEnum::NONRESIDENT, ResidencyEnum::ALL])) {
            throw new InvalidRoomResidency('Client doesn\'t support non resident prices');
        }
    }

    private function getClientResidency(OrderId $orderId): ResidencyEnum
    {
        $order = $this->orderRepository->find($orderId->value());
        if ($order === null) {
            throw new EntityNotFoundException('Order not found');
        }
        $client = $this->clientAdapter->find($order->clientId()->value());
        if ($client === null) {
            throw new EntityNotFoundException('Client not found');
        }

        return $client->residency;
    }
}
