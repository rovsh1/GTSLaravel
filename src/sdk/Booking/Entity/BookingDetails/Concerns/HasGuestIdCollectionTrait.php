<?php

namespace Sdk\Booking\Entity\BookingDetails\Concerns;

use Sdk\Booking\Event\ServiceBooking\GuestBinded;
use Sdk\Booking\Event\ServiceBooking\GuestUnbinded;
use Sdk\Booking\Exception\GuestAlreadyExists;
use Sdk\Booking\ValueObject\GuestId;
use Sdk\Booking\ValueObject\GuestIdCollection;

trait HasGuestIdCollectionTrait
{
    public function guestIds(): GuestIdCollection
    {
        return $this->guestIds;
    }

    public function addGuest(GuestId $id): void
    {
        if ($this->guestIds->has($id)) {
            throw new GuestAlreadyExists('Guest already exists');
        }
        $this->guestIds = new GuestIdCollection([...$this->guestIds->all(), $id]);
        $this->pushEvent(new GuestBinded($this, $id));
    }

    public function removeGuest(GuestId $guestId): void
    {
        if (!$this->guestIds->has($guestId)) {
            throw new GuestAlreadyExists('Guest not found');
        }
        $this->guestIds = new GuestIdCollection(
            array_filter($this->guestIds->all(), fn($id) => !$guestId->isEqual($id))
        );
        $this->pushEvent(new GuestUnbinded($this, $guestId));
    }

    public function guestsCount(): int
    {
        return count($this->guestIds);
    }
}
