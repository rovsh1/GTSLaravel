<?php

namespace Module\Booking\Domain\Shared\Adapter;

use Module\Client\Application\Dto\ClientDto;
use Module\Client\Application\Dto\LegalDto;

interface ClientAdapterInterface
{
    public function find(int $id): ?ClientDto;

    public function findLegal(int $id): ?LegalDto;
}