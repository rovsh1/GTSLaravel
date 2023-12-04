<?php

namespace Module\Generic\Notification\Domain\Recipient;

use Module\Generic\Notification\Domain\Enum\RecipientTypeEnum;

final class HotelContacts implements RecipientInterface
{
    public function type(): RecipientTypeEnum
    {
        return RecipientTypeEnum::HOTEL_CONTACTS;
    }

    public function toArray(): array
    {
        return [];
    }

    public function isEqual(RecipientInterface $recipient): bool
    {
        return $recipient instanceof $this;
    }
}