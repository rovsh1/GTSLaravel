<?php

namespace Pkg\IntegrationEventBus\Queue;

use Illuminate\Queue\Connectors\ConnectorInterface;
use Pkg\IntegrationEventBus\Service\MessageSender;

class Connector implements ConnectorInterface
{
    public function __construct(
        private readonly MessageSender $messageSender
    ) {
    }

    public function connect(array $config)
    {
        return new Queue($this->messageSender);
    }
}
