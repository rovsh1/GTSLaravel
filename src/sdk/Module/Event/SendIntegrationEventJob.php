<?php

namespace Sdk\Module\Event;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Sdk\Module\Contracts\Event\IntegrationEventInterface;
use Sdk\Module\Contracts\Event\IntegrationEventSubscriberInterface;

class SendIntegrationEventJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly string $module,
        public readonly IntegrationEventInterface $event
    ) {
    }

    public function handle(): void
    {
        foreach (app()->modules() as $module) {
            if ($module->is($this->module)) {
                continue;
            }

            $module->boot();

            try {
                $module->get(IntegrationEventSubscriberInterface::class)->handle($this->event);
            } catch (\Throwable $e) {
                throw $e;
            }
        }
    }
}