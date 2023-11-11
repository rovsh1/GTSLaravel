<?php

namespace Module\Generic\Logging\Listener;

use Module\Booking\Moderation\Application\Event\TestEvent;

class TestListener
{
    public function handle(TestEvent $event): void
    {
        dd($event);
    }
}