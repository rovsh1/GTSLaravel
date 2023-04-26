<?php

namespace Module\Services\NotificationManager\Domain\Entity;

interface NotifiableInterface
{
    public function email(): ?string;

    public function phone(): ?string;

    public function isEqual(NotifiableInterface $notifiable): bool;
}