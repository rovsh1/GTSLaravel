<?php

namespace Module\Booking\Hotel\Port\Controllers;

use Module\Booking\Common\Domain\Event\Status\BookingCancelled;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\PortGateway\Request;

class ReservationController
{
    public function __construct(
        private readonly DomainEventDispatcherInterface $eventDispatcher
    ) {}

    //FIXME TEST
    public function cancel(Request $request)
    {
        $this->eventDispatcher->dispatch(new BookingCancelled(12, 'Test cancel'));
    }

}
