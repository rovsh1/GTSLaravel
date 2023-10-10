<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\UseCase;

use Module\Booking\Application\Admin\ServiceBooking\Service\DetailsFactory\DetailsEditor;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateDetails implements UseCaseInterface
{
    public function __construct(
        private readonly DetailsEditor $detailsEditor,
    ) {}

    public function execute(int $bookingId, array $data): void
    {
        $this->detailsEditor->update(new BookingId($bookingId), $data);
    }
}
