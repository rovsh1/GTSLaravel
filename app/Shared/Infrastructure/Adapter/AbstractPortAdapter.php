<?php

namespace GTS\Shared\Infrastructure\Adapter;

abstract class AbstractPortAdapter
{
    protected function request($route, array $attributes = []): mixed
    {
        return app('portGateway')->request($route, $attributes);
    }
}
