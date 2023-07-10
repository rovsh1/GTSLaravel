<?php

namespace Module\Services\PortGateway;

use Module\Services\PortGateway\Exception\BasePortGatewayException;
use Module\Services\PortGateway\Exception\ModuleNotFoundException;
use Module\Shared\Infrastructure\Adapter\PortGatewayInterface;

class Client implements PortGatewayInterface
{
    public function __construct() {}

    public function request(string $route, array $attributes = []): mixed
    {
        return $this->call(Request::fromRoute($route, $attributes));
    }

    public function call(Request $request): mixed
    {
        $module = module($request->module());
        if ($module === null) {
            throw new ModuleNotFoundException("Module '{$request->module()}' not found");
        }

        $module->boot();

        try {
            return $module->get('router')->request($request->path(), $request->attributes());
        } catch (\Throwable $e) {
            throw new BasePortGatewayException($e->getMessage(), $e->getCode(), $e);
        }
    }
}