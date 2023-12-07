<?php

namespace Sdk\Booking\Entity\Details\Concerns;

use Sdk\Booking\Entity\CarBid;
use Sdk\Booking\Entity\HotelAccommodation;
use Sdk\Booking\Event\HotelBooking\GuestBinded as HotelAccommodationGuestBinded;
use Sdk\Booking\Event\HotelBooking\GuestUnbinded as HotelAccommodationGuestUnbinded;
use Sdk\Booking\Event\ServiceBooking\GuestBinded;
use Sdk\Booking\Event\ServiceBooking\GuestUnbinded;
use Sdk\Booking\Event\TransferBooking\GuestBinded as CarBidGuestBinded;
use Sdk\Booking\Event\TransferBooking\GuestUnbinded as CarBidGuestUnbinded;
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

        if ($this instanceof HotelAccommodation) {
            $event = new HotelAccommodationGuestBinded($this, $id);
        } elseif ($this instanceof CarBid) {
            $event = new CarBidGuestBinded($this, $id);
        } else {
            $event = new GuestBinded($this, $id);
        }

        $this->pushEvent($event);
    }

    public function removeGuest(GuestId $guestId): void
    {
        if (!$this->guestIds->has($guestId)) {
            throw new GuestAlreadyExists('Guest not found');
        }
        $this->guestIds = new GuestIdCollection(
            array_filter($this->guestIds->all(), fn($id) => !$guestId->isEqual($id))
        );

        if ($this instanceof HotelAccommodation) {
            $event = new HotelAccommodationGuestUnbinded($this, $guestId);
        } elseif ($this instanceof CarBid) {
            $event = new CarBidGuestUnbinded($this, $guestId);
        } else {
            $event = new GuestUnbinded($this, $guestId);
        }

        $this->pushEvent($event);
    }

    public function guestsCount(): int
    {
        return count($this->guestIds);
    }
}
