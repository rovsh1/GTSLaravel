<?php

namespace Sdk\Module\Routing;

use Illuminate\Support\Str;
use Sdk\Module\Foundation\Module;
use Sdk\Module\PortGateway\Request;

class RouteHandler
{
    public function __construct(private readonly Module $module)
    {
    }

    public function handle(Route $route, array $attributes): mixed
    {
        $request = new Request($route->path(), $attributes);
        $action = $route->action();
        if (is_string($action)) {
            return $this->handleByString($action, $request);
        } elseif (is_array($action)) {
            return $this->handleByArray($action, $request);
        } else {
            throw new \LogicException('Module route [' . $route->path() . '] invalid');
        }
    }

    private function handleByString(string $action, Request $request)
    {
        $action = $this->module->make($action);
        if (method_exists($action, 'handle')) {
            return $action->handle($request);
        } else {
            return $action($request);
        }
    }

    private function handleByArray(array $action, Request $request)
    {
        $controller = $this->module->make($action[0]);

        return $controller->{Str::camel($action[1])}($request);
    }
}
