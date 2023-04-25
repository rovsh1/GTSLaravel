<?php

namespace Module\Services\MailManager\Infrastructure\Queue;

use Module\Services\MailManager\Domain\Service\QueueManagerInterface;
use Illuminate\Contracts\Queue\Queue as QueueContract;
use Illuminate\Queue\Queue;

class MailQueue extends Queue implements QueueContract
{
    public function __construct(
        private readonly QueueManagerInterface $queueManager
    ) {
    }

    public function size($queue = null)
    {
        return $this->queueManager->size();
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
        $queueMessage = $this->queueManager->pop();
        if (!$queueMessage) {
            return null;
        }

        return new MailJob($this->queueManager, $queueMessage);
    }
}
