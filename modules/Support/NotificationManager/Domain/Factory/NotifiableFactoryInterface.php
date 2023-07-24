<?php

namespace Module\Support\NotificationManager\Domain\Factory;

use Module\Support\NotificationManager\Domain\ValueObject\NotifiableList;
use Module\Support\NotificationManager\Domain\ValueObject\NotifiableTypeEnum;

interface NotifiableFactoryInterface
{
    public function fromType(NotifiableTypeEnum $type, ?string $data): NotifiableList;
}
