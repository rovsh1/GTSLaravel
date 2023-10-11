<?php

namespace Service\IntegrationEventGateway;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Module\Shared\Contracts\Event\IntegrationEventInterface;

class SendIntegrationEventJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public IntegrationEventInterface $event)
    {
    }

    public function handle(IntegrationEventDispatcher $dispatcher): void
    {
        $dispatcher->dispatch($this->event);
    }
}