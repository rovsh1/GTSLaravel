<?php

namespace Module\Shared\Infrastructure\Adapter;

use Sdk\Module\Contracts\PortGateway\PortGatewayInterface;

abstract class AbstractPortAdapter
{
    public function __construct(protected readonly PortGatewayInterface $portGateway)
    {
    }

    protected function request($route, array $attributes = []): mixed
    {
        return $this->portGateway->request($route, $attributes);
    }
}
