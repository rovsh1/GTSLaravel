<?php

namespace Module\Support\MailManager\Infrastructure\Queue;

use Illuminate\Contracts\Queue\Queue as QueueContract;
use Illuminate\Queue\Queue;
use Module\Support\MailManager\Domain\Service\QueueManagerInterface;

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
