<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\UseCase;

use Module\Booking\Application\Admin\ServiceBooking\Service\DetailsEditor\DetailsEditorFactory;
use Module\Booking\Domain\Booking\Factory\DetailsRepositoryFactory;
use Module\Booking\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class CopyBooking implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly DetailsEditorFactory $detailsEditorFactory,
        private readonly DetailsRepositoryFactory $detailsRepositoryFactory
    ) {}

    public function execute(int $id): int
    {
        $booking = $this->repository->find(new BookingId($id));
        if ($booking === null) {
            throw new EntityNotFoundException('Booking not found');
        }
        $newBooking = $this->repository->create(
            orderId: $booking->orderId(),
            creatorId: $booking->creatorId(),
            prices: $booking->prices(),
            cancelConditions: $booking->cancelConditions(),
            serviceType: $booking->serviceType(),
            note: $booking->note(),
        );
        $details = $this->detailsRepositoryFactory->build($booking)->find($booking->id());

        $editor = $this->detailsEditorFactory->build($newBooking);
        $editor->create($newBooking->id(), $details->serviceInfo()->id(), []);

        return $newBooking->id()->value();
    }
}
