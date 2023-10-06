<?php

namespace Module\Booking\Domain\ServiceBooking\Support\Concerns;

use Module\Booking\Domain\Shared\ValueObject\GuestId;
use Module\Booking\Domain\Shared\ValueObject\GuestIdsCollection;

trait HasGuestIdCollectionTrait
{
    public function guestIds(): GuestIdsCollection
    {
        return $this->guestIds;
    }

    public function addGuest(GuestId $id): void
    {
        if ($this->guestIds->has($id)) {
            throw new \Exception('Guest already exists');
        }
        $this->guestIds = new GuestIdsCollection([
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
        $this->guestIds = new GuestIdsCollection($newGuestIds);
    }
}
