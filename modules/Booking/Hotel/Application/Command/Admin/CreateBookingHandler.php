<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\Command\Admin;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Booking\Hotel\Domain\Adapter\OrderAdapterInterface;
use Module\Booking\Hotel\Infrastructure\Models\Booking;
use Module\Booking\Hotel\Infrastructure\Models\Hotel\BookingDetails;

class CreateBookingHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly OrderAdapterInterface $orderAdapter
    ) {}

    public function handle(CommandInterface|CreateBooking $command): int
    {
        return \DB::transaction(function () use ($command) {
            $orderId = $command->orderId;
            if ($orderId === null) {
                $orderId = $this->orderAdapter->createOrder($command->clientId);
            }

            $booking = Booking::create([
                'order_id' => $orderId,
                'source' => 1, //@todo hack
                'status' => BookingStatusEnum::CREATED,
                'note' => $command->note,
            ]);

            BookingDetails::create([
                'booking_id' => $booking->id,
                'hotel_id' => $command->hotelId,
                'date_start' => $command->period->getStartDate(),
                'date_end' => $command->period->getEndDate(),
            ]);

            return $booking->id;
        });
    }
}
