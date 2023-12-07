<?php

namespace Sdk\Booking\IntegrationEvent;

use Sdk\Booking\Support\IntegrationEvent\AbstractBookingEvent;

final class NoteModified extends AbstractBookingEvent
{
    public function __construct(
        int $bookingId,
        public readonly ?string $note,
        public readonly ?string $noteBefore,
    ) {
        parent::__construct($bookingId);
    }
}