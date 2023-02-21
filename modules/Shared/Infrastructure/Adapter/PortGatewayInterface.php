<?php

namespace Module\Shared\Infrastructure\Adapter;

interface PortGatewayInterface
{
    public function request(string $route, array $attributes = []): mixed;
}
