<?php

namespace Module\Services\NotificationManager\Domain\Factory;

use Module\Services\NotificationManager\Domain\ValueObject\NotifiableList;
use Module\Services\NotificationManager\Domain\ValueObject\NotifiableTypeEnum;

interface NotifiableFactoryInterface
{
    public function fromType(NotifiableTypeEnum $type, ?string $data): NotifiableList;
}
