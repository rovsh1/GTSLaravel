<?php

declare(strict_types=1);

namespace Module\Client\Application\UseCase;

use Module\Client\Application\Response\ClientDto;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetClient implements UseCaseInterface
{
    public function __construct()
    {
    }

    public function execute(int $id): ClientDto
    {
    }
}
