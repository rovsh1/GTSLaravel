<?php

namespace GTS\Shared\Infrastructure\Bus\Port;

use GTS\Shared\Domain\Adapter\RequestInterface;

interface GatewayInterface
{
    public function call(RequestInterface $request): mixed;
}
