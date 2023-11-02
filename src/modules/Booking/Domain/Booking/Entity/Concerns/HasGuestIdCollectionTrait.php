<?php

namespace Module\Booking\Domain\Booking\Entity\Concerns;

use Module\Booking\Domain\Booking\Exception\GuestAlreadyExists;
use Module\Booking\Domain\Guest\ValueObject\GuestId;
use Module\Booking\Domain\Shared\ValueObject\GuestIdCollection;

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
    }

    public function removeGuest(GuestId $guestId): void
    {
        if (!$this->guestIds->has($guestId)) {
            throw new GuestAlreadyExists('Guest not found');
        }
        $this->guestIds = new GuestIdCollection(
            array_filter($this->guestIds->all(), fn($id) => !$guestId->isEqual($id))
        );
    }

    public function guestsCount(): int
    {
        return count($this->guestIds);
    }
}
