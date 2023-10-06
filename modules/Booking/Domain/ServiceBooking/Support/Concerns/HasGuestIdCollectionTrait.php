<?php

namespace Module\Booking\Domain\ServiceBooking\Support\Concerns;

use Module\Booking\Domain\Shared\ValueObject\GuestId;
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
            throw new \Exception('Guest already exists');
        }
        $this->guestIds = new GuestIdCollection([
            ...$this->guestIds->all(),
            $id
        ]);
    }

    public function removeGuest(GuestId $id): void
    {
        $newGuestIds = [];
        foreach ($this->guestIds as $guestId) {
            if ($guestId->isEqual($id)) {
                continue;
            }
            $newGuestIds[] = $guestId;
        }
        $this->guestIds = new GuestIdCollection($newGuestIds);
    }
}
