<?php

namespace Sdk\Booking\Entity\BookingDetails\Concerns;

trait HasMeetingTabletTrait
{
    public function meetingTablet(): ?string
    {
        return $this->meetingTablet;
    }

    public function setMeetingTablet(?string $meetingTablet): void
    {
        $this->meetingTablet = $meetingTablet;
    }
}
