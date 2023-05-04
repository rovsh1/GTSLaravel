<?php

namespace Module\Shared\Infrastructure\Adapter;

use Custom\Framework\Contracts\PortGateway\PortGatewayInterface;
use Custom\Framework\Foundation\Module;

abstract class AbstractModuleAdapter
{
    protected Module $module;

    abstract protected function getModuleKey(): string;

    public function __construct(protected readonly PortGatewayInterface $portGateway)
    {
        $this->module = module($this->getModuleKey());
    }

    protected function request(string $route, array $attributes = [])
    {
        return $this->portGateway->request($this->module->name() . '/' . $route, $attributes);
    }
}
