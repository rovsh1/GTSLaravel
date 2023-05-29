<?php

namespace Sdk\Module\Notification;

use Sdk\Module\Contracts\Notification\NotificationGatewayInterface;
use Sdk\Module\Contracts\Notification\NotificationInterface;

class NotificationGateway implements NotificationGatewayInterface
{
    public function __construct() {}

    public function send(NotificationInterface $notification)
    {

    }
}
