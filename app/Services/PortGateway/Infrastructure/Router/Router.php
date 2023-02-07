<?php

namespace GTS\Services\PortGateway\Infrastructure\Router;

use GTS\Services\PortGateway\Infrastructure\Port\Exception;

class Router
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

        $moduleClient = $this->clientFactory();

        return $moduleClient->request($request->path(), $request->attributes());
    }

    private function clientFactory()
    {
        //TODO get module router
    }
}
