<?php

namespace Module\Booking\Moderation\Domain\Booking\Event;

use Module\Booking\EventSourcing\Domain\Service\AttributeDiff;
use Module\Booking\EventSourcing\Domain\Service\AttributeTypeEnum;
use Module\Booking\EventSourcing\Domain\Service\ChangesInterface;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Event\HasChangesInterface;
use Sdk\Module\Contracts\Event\DomainEventInterface;

class NoteChanged implements DomainEventInterface, HasChangesInterface
{
    public function __construct(
        public readonly Booking $booking,
        public readonly string|null $noteBefore,
    ) {
    }

    public function changes(): ChangesInterface
    {
        return new AttributeDiff(
            'note',
            AttributeTypeEnum::STRING,
            $this->noteBefore,
            $this->booking->note()
        );
    }
}