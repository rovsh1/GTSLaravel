<?php

namespace GTS\Shared\Domain\Port;

interface PortGatewayInterface
{
    public function call(RequestInterface $request): mixed;
}
