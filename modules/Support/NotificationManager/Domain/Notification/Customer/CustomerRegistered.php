<?php

namespace Module\Support\NotificationManager\Domain\Notification\Customer;

use Module\Support\NotificationManager\Domain\Entity\NotifiableInterface;
use Module\Support\NotificationManager\Domain\Notification\NotificationInterface;

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