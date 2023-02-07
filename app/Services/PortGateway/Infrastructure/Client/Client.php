<?php

namespace GTS\Services\PortGateway\Infrastructure\Client;

use GTS\Shared\Infrastructure\Adapter\PortGatewayInterface;

class Client implements PortGatewayInterface
{
    public function __construct() {}

    public function request(string $route, array $attributes = [])
    {
        return $this->call(Request::fromRoute($route, $attributes));
    }

    public function call(Request $request): mixed
    {
        $module = module($request->module());
        if ($module === null) {
            throw new Exception\ModuleNotFoundException("Module '{$request->module()}' not found");
        }

        $module->boot();

        $route = $module->get('router')->get($request->path());

        $action = $module->make($route);

        return $action->handle((object)$request->attributes());
    }
}
