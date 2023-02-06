<?php

namespace GTS\Services\PortGateway;

use Illuminate\Contracts\Container\Container;

class Gateway implements GatewayInterface
{
    public function __construct(
        private readonly Container $container
    ) {}

    public function call(RequestInterface $request): mixed
    {
        $module = module($request->module());
        if ($module === null) {
            throw new Domain\Exception\ModuleNotFoundException("Module '{$request->module()}' not found");
        }
        $portInterface = $module->portNamespace($request->port());
        if (!interface_exists($portInterface)) {
            throw new Domain\Exception\PortNotFoundException("Port '{$request->port()}' not found");
        }
        $port = $this->container->make($portInterface);
        $action = $request->method();
        if (!method_exists($port, $action)) {
            throw new Domain\Exception\UndefinedPortMethodException("Method '{$request->method()}' is undefined");
        }

        return $port->$action(...$request->arguments());
    }
}
