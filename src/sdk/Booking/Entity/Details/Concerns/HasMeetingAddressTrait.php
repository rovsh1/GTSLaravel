<?php

namespace Sdk\Booking\Entity\Details\Concerns;

use Sdk\Booking\Event\DetailsFieldUpdated;

trait HasMeetingAddressTrait
{
    public function meetingAddress(): ?string
    {
        return $this->meetingAddress;
    }

    public function setMeetingAddress(?string $meetingAddress): void
    {
        $valueBefore = $this->meetingAddress;
        $this->meetingAddress = $meetingAddress;
        $this->pushEvent(new DetailsFieldUpdated($this, 'meetingAddress', $meetingAddress, $valueBefore));
    }
}
