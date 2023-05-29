<?php

namespace Sdk\Module\PortGateway\Client;

use Sdk\Module\Contracts\PortGateway\PortGatewayInterface;
use Sdk\Module\PortGateway\Exception\BasePortGatewayException;
use Sdk\Module\PortGateway\Exception\ModuleNotFoundException;

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
            throw new BasePortGatewayException($e->getMessage(), (int)$e->getCode(), $e);
        }
    }
}
