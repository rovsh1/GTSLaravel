<?php

namespace Sdk\Booking\Contracts\Entity;

use Sdk\Booking\ValueObject\ServiceInfo;

interface TransferDetailsInterface extends DetailsInterface
{
    public function serviceInfo(): ServiceInfo;
}
