<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\UseCase\Admin;

use Module\Booking\HotelBooking\Domain\Repository\BookingGuestRepositoryInterface;
use Module\Booking\HotelBooking\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\HotelBooking\Domain\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Order\Domain\Repository\OrderRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class CopyBooking implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly RoomBookingRepositoryInterface $roomBookingRepository,
        private readonly BookingGuestRepositoryInterface $bookingGuestRepository,
        private readonly OrderRepositoryInterface $orderRepository,
    ) {}

    public function execute(int $id): int
    {
        $booking = $this->repository->find($id);
        if ($booking === null) {
            throw new EntityNotFoundException('Booking not found');
        }
        $order = $this->orderRepository->find($booking->orderId()->value());
        if ($order === null) {
            throw new EntityNotFoundException('Order not found');
        }
        $newBooking = $this->repository->create(
            $booking->orderId(),
            $order->currency(),
            $booking->creatorId(),
            $booking->period(),
            $booking->note(),
            $booking->hotelInfo(),
            $booking->cancelConditions(),
            $booking->quotaProcessingMethod(),
        );

        $rooms = $this->roomBookingRepository->get($id);
        foreach ($rooms as $room) {
            $roomBooking = $this->roomBookingRepository->create(
                $newBooking->id(),
                $room->roomInfo(),
                $room->details(),
                $room->price()
            );
            //@todo УТочнить у Анвара/Бахтиера - копирование делается в тот же заказ?
            foreach ($room->guestIds() as $guestId) {
                $this->bookingGuestRepository->bind($roomBooking->id(), $guestId);
            }
        }

        return $newBooking->id()->value();
    }
}
