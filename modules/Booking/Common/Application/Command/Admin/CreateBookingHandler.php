<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\Command\Admin;

use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Booking\Common\Infrastructure\Models\Booking;
use Module\Booking\Order\Application\Command\CreateOrder;
use Sdk\Module\Contracts\Bus\CommandBusInterface;
use Sdk\Module\Contracts\Bus\CommandHandlerInterface;
use Sdk\Module\Contracts\Bus\CommandInterface;

class CreateBookingHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {}

    public function handle(CommandInterface|CreateBooking $command): int
    {
        return \DB::transaction(function () use ($command) {
            $orderId = $command->orderId;
            if ($orderId === null) {
                $orderId = $this->commandBus->execute(new CreateOrder($command->clientId));
            }

            $booking = Booking::create([
                'order_id' => $orderId,
                'source' => 1, //@todo hack источник создания брони
                'status' => BookingStatusEnum::CREATED,
                'note' => $command->note,
                'creator_id' => $command->creatorId,
                'type' => $command->type
            ]);
            //@todo ивент создания брони

            return $booking->id;
        });
    }
}
