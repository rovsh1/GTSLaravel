<?php

namespace Module\Shared\Domain\Entity;

use Module\Shared\Domain\ValueObject\Id;

interface EntityInterface
{
    public function id(): Id;
}
