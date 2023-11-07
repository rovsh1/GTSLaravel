<?php

namespace Module\Generic\Logging\Listener;

use Module\Booking\Shared\Application\Event\TestEvent;

class TestListener
{
    public function handle(TestEvent $event): void
    {
        dd($event);
    }
}