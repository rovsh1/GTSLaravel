<?php

namespace Module\Generic\NotificationManager\Domain\Listener\Customer;

use Module\Generic\NotificationManager\Domain\Notification\Customer\CustomerRegistered;
use Module\Generic\NotificationManager\Domain\Support\Listener\AdministratorsNotificationTrait;
use Module\Generic\NotificationManager\Domain\Support\Listener\CustomerNotificationTrait;
use Sdk\Module\Contracts\Event\IntegrationEventInterface;
use Sdk\Module\Contracts\Event\IntegrationEventListenerInterface;

class CustomerRegisteredListener implements IntegrationEventListenerInterface
{
    use CustomerNotificationTrait;
    use AdministratorsNotificationTrait;

    public function handle(IntegrationEventInterface $event): void
    {
        $eventPayload = $event->payload();
        $notification = new CustomerRegistered($eventPayload['user_id'], $eventPayload['registrationData']);

        $this->notifyCustomerById($notification->customerId, $notification);

        $this->notifyAdministrators($notification);
    }
}
