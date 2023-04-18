<?php

namespace Module\Booking\Hotel\Domain\ValueObject;

use Module\Booking\Common\Domain\ValueObject\ClientTypeEnum;

class Client extends \Module\Booking\Common\Domain\ValueObject\Client
{
    public function __construct(
        int                     $id,
        ClientTypeEnum          $type,
        private readonly string $fullName,
    )
    {
        parent::__construct($id, $type);
    }

    public function fullName(): string
    {
        return $this->fullName;
    }
}
