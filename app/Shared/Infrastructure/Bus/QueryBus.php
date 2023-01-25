<?php

namespace GTS\Shared\Infrastructure\Bus;

use Illuminate\Contracts\Container\Container;

use GTS\Shared\Application\Query\QueryBusInterface;
use GTS\Shared\Application\Query\QueryInterface;

class QueryBus implements QueryBusInterface
{

    private array $handlers = [];

    public function __construct(private readonly Container $container) { }

    public function subscribe(string $queryClassName, string $handlerClassName)
    {
        $this->handlers[$queryClassName] = $handlerClassName;
    }

    public function execute(QueryInterface $query): mixed
    {
        /*foreach ($this->middlewareHandlers as $middlewareHandler) {
            $middlewareHandler($command);
        }*/

        $queryHandler = $this->getQueryHandler($query);
        if (!$queryHandler)
            throw new \Exception('Query [' . $query::class . '] handler undefined');

        return $queryHandler->handle($query);
    }

    private function hasQueryHandler(QueryInterface $query): bool
    {
        return array_key_exists(get_class($query), $this->handlers);
    }

    private function getQueryHandler(QueryInterface $query)
    {
        if ($this->hasQueryHandler($query))
            return $this->container->make($this->handlers[get_class($query)]);

        $handlerClass = str_replace('Application', 'Infrastructure', $query::class) . 'Handler';
        if (class_exists($handlerClass))
            return $this->container->make($handlerClass);

        return null;
    }
}
