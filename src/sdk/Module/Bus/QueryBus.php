<?php

namespace Sdk\Module\Bus;

use Sdk\Module\Container\Container;

class QueryBus implements \Sdk\Module\Contracts\Bus\QueryBusInterface
{

    private array $handlers = [];

    public function __construct(private readonly Container $container) {}

    public function subscribe(string $queryClassName, string $handlerClassName)
    {
        $this->handlers[$queryClassName] = $handlerClassName;
    }

    public function execute(\Sdk\Module\Contracts\Bus\QueryInterface $query): mixed
    {
        /*foreach ($this->middlewareHandlers as $middlewareHandler) {
            $middlewareHandler($command);
        }*/

        $queryHandler = $this->getQueryHandler($query);
        if (!$queryHandler) {
            throw new \Exception('Query [' . $query::class . '] handler undefined');
        }

        return $queryHandler->handle($query);
    }

    private function hasQueryHandler(\Sdk\Module\Contracts\Bus\QueryInterface $query): bool
    {
        return array_key_exists(get_class($query), $this->handlers);
    }

    private function getQueryHandler(\Sdk\Module\Contracts\Bus\QueryInterface $query)
    {
        if ($this->hasQueryHandler($query)) {
            return $this->container->make($this->handlers[get_class($query)]);
        }

        $handlerClass = $query::class . 'Handler';
        if (class_exists($handlerClass)) {
            return $this->container->make($handlerClass);
        }

        return null;
    }
}
