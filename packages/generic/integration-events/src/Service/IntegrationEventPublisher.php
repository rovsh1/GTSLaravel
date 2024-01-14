<?php

namespace Pkg\IntegrationEventBus\Service;

use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Pkg\IntegrationEventBus\Entity\Message;
use Sdk\Shared\Contracts\Event\IntegrationEventInterface;
use Sdk\Shared\Contracts\Event\IntegrationEventPublisherInterface;
use Sdk\Shared\Contracts\Service\ApplicationContextInterface;

class IntegrationEventPublisher implements IntegrationEventPublisherInterface
{
    private const QUEUE = 'integration_events';

    private Connection $connection;

    private bool $async;

    public function __construct(
        private readonly ApplicationContextInterface $context
    ) {
        $this->async = env('INTEGRATION_EVENTS_ASYNC', true);
    }

    public function publish(string $originator, IntegrationEventInterface ...$events): void
    {
        if ($this->async) {
            $this->publishAsync($originator, ... $events);
        } else {
            $this->publishSync($originator, ... $events);
        }
    }

    private function publishSync(string $originator, IntegrationEventInterface ...$events): void
    {
        /** @var MessageSender $sender */
        $sender = app(MessageSender::class);
        foreach ($events as $event) {
            $sender->send(Message::deserialize([
                'id' => Str::orderedUuid()->toString(),
                'originator' => $originator,
                'status' => 0,
                'event' => $event::class,
                'payload' => serialize($event),
                'context' => $this->context->toArray(),
            ]));
        }
    }

    private function publishAsync(string $originator, IntegrationEventInterface ...$events): void
    {
        foreach ($events as $event) {
            $this->connection()->rPush(
                self::QUEUE,
                json_encode([
                    'id' => Str::orderedUuid()->toString(),
                    'originator' => $originator,
                    'status' => 0,
                    'event' => $event::class,
                    'payload' => serialize($event),
                    'context' => $this->context->toArray(),
                    'timestamp' => time()
                ])
            );
        }
    }

    private function connection(): Connection
    {
        return $this->connection
            ?? ($this->connection = Redis::connection('events'));
    }
}