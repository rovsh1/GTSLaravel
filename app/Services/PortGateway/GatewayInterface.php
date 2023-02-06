<?php

namespace GTS\Services\PortGateway;

interface GatewayInterface
{
    public function call(RequestInterface $request): mixed;
}
