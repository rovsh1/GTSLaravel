<?php

namespace GTS\Shared\Infrastructure\Bus;

use Illuminate\Contracts\Container\Container;

class PortGateway implements PortGatewayInterface
{
    public function __construct(
        private readonly Container $container
    ) {}

    public function call(string $route, mixed $requestDto): mixed
    {
        $segments = explode('.', $route);

        $module = ucfirst(array_shift($segments));
        $action = array_pop($segments);
        $portClass = implode('\\', $segments) . 'PortInterface';
        $portClass = module($module)->namespace('Infrastructure\\Port\\' . $portClass);
        if (!class_exists($portClass))
            throw new \Exception('Port not found');

        $port = $this->container->make($portClass);

        if (!method_exists($port, $action))
            throw new \Exception('Port method not found');

        return $port->$action($requestDto);
    }
}
