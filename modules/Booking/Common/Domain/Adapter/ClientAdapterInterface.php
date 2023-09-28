<?php

namespace Module\Booking\Common\Domain\Adapter;

use Module\Client\Application\Dto\ClientDto;
use Module\Client\Application\Dto\LegalDto;

interface ClientAdapterInterface
{
    public function find(int $id): ?ClientDto;

    public function findLegal(int $id): ?LegalDto;
}