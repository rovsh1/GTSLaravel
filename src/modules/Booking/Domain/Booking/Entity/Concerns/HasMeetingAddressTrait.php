<?php

namespace Module\Booking\Domain\Booking\Entity\Concerns;

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
