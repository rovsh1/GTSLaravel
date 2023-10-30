<?php

namespace Module\Generic\NotificationManager\Domain\Notification\Customer;

use Module\Generic\NotificationManager\Domain\Entity\NotifiableInterface;
use Module\Generic\NotificationManager\Domain\Notification\NotificationInterface;

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