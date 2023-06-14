<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\Command\Admin;

use Module\Booking\Hotel\Domain\Adapter\HotelAdapterInterface;
use Module\Booking\Hotel\Infrastructure\Repository\BookingRepository;
use Module\Booking\Order\Application\Command\CreateOrder;
use Sdk\Module\Contracts\Bus\CommandBusInterface;
use Sdk\Module\Contracts\Bus\CommandHandlerInterface;
use Sdk\Module\Contracts\Bus\CommandInterface;

class CreateBookingHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly HotelAdapterInterface $hotelAdapter,
        private readonly BookingRepository $repository
    ) {}

    public function handle(CommandInterface|CreateBooking $command): int
    {
        $orderId = $command->orderId;
        if ($orderId === null) {
            $orderId = $this->commandBus->execute(new CreateOrder($command->clientId));
        }

        $hotelDto = $this->hotelAdapter->findById($command->hotelId);
        $markupSettings = $this->hotelAdapter->getMarkupSettings($command->hotelId);
        $booking = $this->repository->create(
            $orderId,
            $command->creatorId,
            $command->hotelId,
            $command->period,
            $command->note,
            $hotelDto,
            $markupSettings
        );

        return $booking->id()->value();
    }


}
