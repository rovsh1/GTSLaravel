<?php

namespace Custom\Framework\Notification;

use Custom\Framework\Contracts\Notification\NotificationGatewayInterface;
use Custom\Framework\Contracts\Notification\NotificationInterface;

class NotificationGateway implements NotificationGatewayInterface
{
    public function __construct() {}

    public function send(NotificationInterface $notification)
    {

    }
}
