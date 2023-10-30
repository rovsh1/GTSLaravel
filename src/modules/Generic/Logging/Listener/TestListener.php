<?php

namespace Module\Generic\Logging\Listener;

use Module\Booking\Application\Admin\Shared\Event\TestEvent;

class TestListener
{
    public function handle(TestEvent $event): void
    {
        dd($event);
    }
}