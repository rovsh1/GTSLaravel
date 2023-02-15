<?php

namespace GTS\Services\PortGateway;

class Client
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

        try {
            return $module->get('router')->request($request->path(), $request->attributes());
        } catch (\Throwable $e) {
            throw new Exception\BasePortGatewayException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
