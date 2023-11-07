<?php

namespace Module\Booking\Shared\Domain\Shared\Adapter;

use Module\Client\Application\Admin\Dto\ClientDto;
use Module\Client\Application\Admin\Dto\LegalDto;

interface ClientAdapterInterface
{
    public function find(int $id): ?ClientDto;

    public function findLegal(int $id): ?LegalDto;
}
