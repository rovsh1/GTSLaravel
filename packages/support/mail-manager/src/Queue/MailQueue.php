<?php

namespace Pkg\MailManager\Queue;

use Illuminate\Contracts\Queue\Queue as QueueContract;
use Illuminate\Queue\Queue;
use Pkg\MailManager\Contracts\MailManagerInterface;
use Pkg\MailManager\Contracts\QueueStorageInterface;

class MailQueue extends Queue implements QueueContract
{
    public function __construct(
        private readonly MailManagerInterface $queueManager,
        private readonly QueueStorageInterface $queueStorage,
    ) {
    }

    public function size($queue = null)
    {
        return $this->queueStorage->waitingCount();
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
        $queueMessage = $this->queueStorage->findWaiting();
        if (!$queueMessage) {
            return null;
        }

        return new MailJob($this->queueManager, $queueMessage);
    }

    public function readyNow($queue = null)
    {
        return $this->queueStorage->waitingCount();
    }
}
