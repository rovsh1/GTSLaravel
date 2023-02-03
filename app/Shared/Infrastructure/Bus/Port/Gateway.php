<?php

namespace GTS\Shared\Infrastructure\Bus\Port;

use GTS\Shared\Domain\Adapter\Manifest\Analyser;
use GTS\Shared\Domain\Adapter\RequestInterface;
use GTS\Shared\Infrastructure\Bus\Port\Exception\InvalidArgumentsException;
use GTS\Shared\Infrastructure\Bus\Port\Exception\ModuleNotFoundException;
use GTS\Shared\Infrastructure\Bus\Port\Exception\PortNotFoundException;
use GTS\Shared\Infrastructure\Bus\Port\Exception\UndefinedPortMethodException;
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
            throw new ModuleNotFoundException("Module '{$request->module()}' not found");
        }
        $portInterface = $module->portNamespace($request->port());
        if (!interface_exists($portInterface)) {
            throw new PortNotFoundException("Port '{$request->port()}' not found");
        }
        $port = $this->container->make($portInterface);
        $action = $request->method();
        if (!method_exists($port, $action)) {
            throw new UndefinedPortMethodException("Method '{$request->method()}' is undefined");
        }

        $manifestAnalyzer = new Analyser($module->manifestPath());
        $rules = $manifestAnalyzer->getMethodArgumentsRules($request->port(), $request->method());
        $validator = \Validator::make($request->arguments(), $rules);
        //@todo валидация классов и интерфейсов
        if (!$validator->passes()) {
            throw new InvalidArgumentsException();
        }

        return $port->$action(...$request->arguments());
    }
}
