<?php

namespace Module\Booking\PriceCalculator\Domain\Adapter;

use Module\Client\Application\Response\ClientDto;

interface ClientAdapterInterface
{
    public function find(int $id): ?ClientDto;
}
