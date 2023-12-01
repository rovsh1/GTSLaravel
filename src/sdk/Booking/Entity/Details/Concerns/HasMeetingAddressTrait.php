<?php

namespace Sdk\Booking\Entity\Details\Concerns;

trait HasMeetingAddressTrait
{
    public function meetingAddress(): ?string
    {
        return $this->meetingAddress;
    }

    public function setMeetingAddress(?string $meetingAddress): void
    {
        $this->meetingAddress = $meetingAddress;
    }
}
