<?php

namespace Module\Services\NotificationManager\Domain\Listener;

use Custom\Framework\Contracts\Event\IntegrationEventInterface;
use Custom\Framework\Contracts\Event\IntegrationEventListenerInterface;
use Module\Services\NotificationManager\Domain\Notification\CustomerRegistered;

class CustomerRegisteredListener implements IntegrationEventListenerInterface
{
    public function handle(IntegrationEventInterface $event): void
    {
        $event->payload();
        //dd($event);
        $customer = 1;
        $registrationData = [];
        $notification = new CustomerRegistered($registrationData);

        $customer->notify($notification);

        $notifiableList = $notificationManager->getNotificationRecipients($notification);
        foreach ($notifiableList as $notifiable) {
            $notifiable->notify($notification);
        }
    }
}
