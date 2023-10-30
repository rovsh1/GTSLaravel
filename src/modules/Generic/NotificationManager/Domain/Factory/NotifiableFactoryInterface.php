<?php

namespace Module\Generic\NotificationManager\Domain\Factory;

use Module\Generic\NotificationManager\Domain\ValueObject\NotifiableList;
use Module\Generic\NotificationManager\Domain\ValueObject\NotifiableTypeEnum;

interface NotifiableFactoryInterface
{
    public function fromType(NotifiableTypeEnum $type, ?string $data): NotifiableList;
}
