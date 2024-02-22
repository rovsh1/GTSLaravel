<?php

namespace Pkg\IntegrationEventBus\Service;

use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Pkg\IntegrationEventBus\Entity\Message;
use Sdk\Shared\Contracts\Event\IntegrationEventInterface;
use Sdk\Shared\Contracts\Event\IntegrationEventPublisherInterface;

class IntegrationEventPublisher implements IntegrationEventPublisherInterface
{
    private const QUEUE = 'integration_events';

    private Connection $connection;

    private bool $async;

    public function __construct()
    {
        $this->async = env('INTEGRATION_EVENTS_ASYNC', true);
    }

    public function publish(string $originator, IntegrationEventInterface $event, array $context): void
    {
        if ($this->async) {
            $this->publishAsync($originator, $event, $context);
        } else {
            $this->publishSync($originator, $event, $context);
        }
    }

    private function publishSync(string $originator, IntegrationEventInterface $event, array $context): void
    {
        /** @var MessageSender $sender */
        $sender = app(MessageSender::class);
        $sender->send(Message::deserialize([
            'id' => Str::orderedUuid()->toString(),
            'originator' => $originator,
            'status' => 0,
            'event' => $event::class,
            'payload' => serialize($event),
            'context' => $context,
        ]));
    }

    private function publishAsync(string $originator, IntegrationEventInterface $event, array $context): void
    {
        $this->connection()->rPush(
            self::QUEUE,
            json_encode([
                'id' => Str::orderedUuid()->toString(),
                'originator' => $originator,
                'status' => 0,
                'event' => $event::class,
                'payload' => serialize($event),
                'context' => $context,
                'timestamp' => time()
            ])
        );
    }

    private function connection(): Connection
    {
        return $this->connection
            ?? ($this->connection = Redis::connection('events'));
    }
}