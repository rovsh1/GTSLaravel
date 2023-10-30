<?php

namespace Module\Support\IntegrationEventBus\Queue;

use Illuminate\Contracts\Queue\Queue as QueueContract;
use Illuminate\Queue\Queue as Base;
use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Facades\Redis;
use Module\Support\IntegrationEventBus\Entity\Message;
use Module\Support\IntegrationEventBus\Service\MessageSender;

class Queue extends Base implements QueueContract
{
    private const QUEUE = 'integration_events';

    private Connection $connection;

    public function __construct(
        private readonly MessageSender $messageSender
    ) {
        $this->connection = Redis::connection('events');
    }

    public function size($queue = null)
    {
        return (int)$this->connection->lLen(self::QUEUE);
    }

    public function push($job, $data = '', $queue = null)
    {
    }

    public function pushRaw($payload, $queue = null, array $options = [])
    {
    }

    public function later($delay, $job, $data = '', $queue = null)
    {
    }

    public function pop($queue = null)
    {
        $encoded = $this->connection->lPop(self::QUEUE);
        if (!$encoded) {
            return null;
        }

        $payload = json_decode($encoded, true);

        return new Job($this->messageSender, Message::deserialize($payload));
    }
}
