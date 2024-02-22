<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\ServiceBooking;

use Module\Booking\Moderation\Application\Service\DetailsEditor\DetailsEditorFactory;
use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateDetailsField implements UseCaseInterface
{
    public function __construct(
        private readonly DetailsEditorFactory $detailsEditorFactory,
        private readonly BookingUnitOfWorkInterface $bookingUnitOfWork,
    ) {}

    public function execute(int $bookingId, string $field, mixed $value): void
    {
        $details = $this->bookingUnitOfWork->getDetails(new BookingId($bookingId));
        $editor = $this->detailsEditorFactory->build($details->serviceType());
        $editor->update($details, [$field => $value]);
        $this->bookingUnitOfWork->commit();
    }
}
