<?php

namespace GTS\Shared\Infrastructure\Adapter;

abstract class AbstractPortAdapter
{
    public function __construct(
        protected readonly PortGatewayInterface $portGateway
    ) {}

    protected function request($route, array $attributes = []): mixed
    {
        return $this->portGateway->request($route, $attributes);
    }
}
