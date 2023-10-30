<?php

namespace Module\Generic\NotificationManager\Domain\Support\Listener;

use Module\Generic\NotificationManager\Domain\Notification\NotificationInterface;

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
