<?php

namespace GTS\Services\PortGateway\Infrastructure\Service;

use Illuminate\Contracts\Container\Container;

use GTS\Shared\Domain\Port\PortGatewayInterface;
use GTS\Shared\Domain\Port\RequestInterface;

class PortGateway implements PortGatewayInterface
{
    public function __construct(
        private readonly Container $container
    ) {}

    public function call(RequestInterface $request): mixed
    {
        $module = module($request->module());
        if ($module === null) {
            throw new Exception\ModuleNotFoundException("Module '{$request->module()}' not found");
        }
        $portInterface = $module->portNamespace($request->port());
        if (!interface_exists($portInterface)) {
            throw new Exception\PortNotFoundException("Port '{$request->port()}' not found");
        }
        $port = $this->container->make($portInterface);
        $action = $request->method();
        if (!method_exists($port, $action)) {
            throw new Exception\UndefinedPortMethodException("Method '{$request->method()}' is undefined");
        }

        return $port->$action(...$request->arguments());
    }
}
