<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\Command\Admin;

use Module\Booking\Common\Domain\Adapter\OrderAdapterInterface;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Booking\Common\Infrastructure\Models\Booking;
use Sdk\Module\Contracts\Bus\CommandHandlerInterface;
use Sdk\Module\Contracts\Bus\CommandInterface;

class CreateBookingHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly OrderAdapterInterface $orderAdapter,
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
                'source' => 1, //@todo hack источник создания брони
                'status' => BookingStatusEnum::CREATED,
                'note' => $command->note,
                'creator_id' => $command->creatorId,
                'type' => $command->type
            ]);

            return $booking->id;
        });
    }
}
