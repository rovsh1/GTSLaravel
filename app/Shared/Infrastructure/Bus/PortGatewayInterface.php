<?php

namespace GTS\Shared\Infrastructure\Bus;

interface PortGatewayInterface
{
    public function call(string $route, mixed $requestDto): mixed;
}
