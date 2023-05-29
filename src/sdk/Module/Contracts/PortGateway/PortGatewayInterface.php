<?php

namespace Sdk\Module\Contracts\PortGateway;

interface PortGatewayInterface
{
    public function request(string $route, array $attributes = []): mixed;
}
