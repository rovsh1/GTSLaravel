<?php

namespace Module\Services\NotificationManager\Domain\Notification;

use Module\Services\NotificationManager\Domain\Entity\NotifiableInterface;

class CustomerRegistered implements NotificationInterface
{
    public function __construct(
        public readonly int $customerId,
        //public readonly string $customerPresentation,
        public readonly array $registrationData
    )
    {
    }

    public function channels(NotifiableInterface $notifiable)
    {
    }
}