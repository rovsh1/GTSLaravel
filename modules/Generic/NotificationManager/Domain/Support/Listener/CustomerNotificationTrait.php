<?php

namespace Module\Generic\NotificationManager\Domain\Support\Listener;

use Module\Generic\NotificationManager\Domain\Notification\NotificationInterface;

trait CustomerNotificationTrait
{

    public function notifyCustomerById(int $customerId, NotificationInterface $notification)
    {
    }

    public function notifyCustomer(NotificationInterface $notification): void
    {
//        $notifiableList = $notificationManager->getNotificationRecipients($notification);
//        foreach ($notifiableList as $notifiable) {
//            $notifiable->notify($notification);
//        }
    }
}
