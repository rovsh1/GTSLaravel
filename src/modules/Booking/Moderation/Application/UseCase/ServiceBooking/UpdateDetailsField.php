<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\ServiceBooking;

use Module\Booking\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Moderation\Application\Service\DetailsEditor\DetailsEditorFactory;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class UpdateDetailsField implements UseCaseInterface
{
    public function __construct(
        private readonly DetailsEditorFactory $detailsEditorFactory,
        private readonly BookingRepositoryInterface $bookingRepository,
    ) {}

    public function execute(int $bookingId, string $field, mixed $value): void
    {
        $booking = $this->bookingRepository->find(new BookingId($bookingId));
        if ($booking === null) {
            throw new EntityNotFoundException('Booking not found');
        }
        $editor = $this->detailsEditorFactory->build($booking);
        $editor->update($booking->id(), [$field => $value]);
    }
}
