<?php

namespace Sdk\Booking\Entity\Details\Concerns;

use Sdk\Booking\Event\DetailsFieldUpdated;

trait HasMeetingTabletTrait
{
    public function meetingTablet(): ?string
    {
        return $this->meetingTablet;
    }

    public function setMeetingTablet(?string $meetingTablet): void
    {
        $valueBefore = $this->meetingTablet;
        $this->meetingTablet = $meetingTablet;
        $this->pushEvent(new DetailsFieldUpdated($this, 'meetingTablet', $meetingTablet, $valueBefore));
    }
}
