<?php

namespace Custom\Framework\Contracts\PortGateway;

interface PortGatewayInterface
{
    public function request(string $route, array $attributes = []): mixed;
}
