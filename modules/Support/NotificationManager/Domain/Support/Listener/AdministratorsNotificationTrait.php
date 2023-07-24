<?php

namespace Module\Support\NotificationManager\Domain\Support\Listener;

use Module\Support\NotificationManager\Domain\Notification\NotificationInterface;

trait AdministratorsNotificationTrait
{
    public function notifyAdministrators(NotificationInterface $notification): void
    {
//        $notifiableList = $notificationManager->getNotificationRecipients($notification);
//        foreach ($notifiableList as $notifiable) {
//            $notifiable->notify($notification);
//        }
    }
}
