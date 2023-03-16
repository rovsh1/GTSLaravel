<?php

namespace App\Core\Support\Adapters;

use Module\Shared\Infrastructure\Adapter\PortGatewayInterface;

abstract class AbstractPortAdapter
{
    protected string $module;

    public function __construct(protected readonly PortGatewayInterface $portGateway) { }

    protected function request(string $route, array $attributes = [])
    {
        return $this->portGateway->request($this->module . '/' . $route, $attributes);
    }
}