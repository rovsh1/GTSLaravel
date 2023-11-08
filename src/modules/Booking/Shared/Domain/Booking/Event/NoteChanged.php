<?php

namespace Module\Booking\Shared\Domain\Booking\Event;

use Module\Booking\Moderation\Domain\Booking\Service\ChangeHistory\AttributeDiff;
use Module\Booking\Moderation\Domain\Booking\Service\ChangeHistory\AttributeTypeEnum;
use Module\Booking\Moderation\Domain\Booking\Service\ChangeHistory\ChangesInterface;
use Module\Booking\Shared\Domain\Booking\Booking;
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