<?php

namespace Custom\Framework\Bus;

use Custom\Framework\Container\Container;

class CommandBus implements CommandBusInterface
{
    private array $handlers = [];

    private array $handlersMiddlewares = [
        Middleware\UseDatabaseTransactions::class => \Custom\Framework\Bus\Middleware\UseDatabaseTransactionMiddleware::class
    ];

    public function __construct(
        private readonly Container $container,
        private readonly array $middlewares = [],
    ) {}

    public function subscribe(string $commandClassName, string $handlerClassName)
    {
        $this->handlers[$commandClassName] = $handlerClassName;
    }

    public function execute(CommandInterface $command): mixed
    {
        $commandHandler = $this->getCommandHandler($command);
        if (!$commandHandler)
            throw new \Exception('Command [' . $command::class . '] handler undefined');

        $action = fn($command) => $commandHandler->handle($command);

        $middlewares = array_merge($this->getCommandMiddlewares($commandHandler), $this->middlewares);
        //$middlewares = array_unique($middlewares);
        $middlewares = array_map(fn($middleware) => $this->container->make($middleware), $middlewares);
        foreach ($middlewares as $middleware) {
            $action = fn($command) => $middleware->handle($command, $action);
        }

        $result = $action($command);

        $this->terminateMiddlewares($command, $middlewares);

        //$events = $this->container->get(EventsPipelineInterface::class)->getEvents();
        //dispatch events

        return $result;
    }

    private function hasCommandHandler(CommandInterface $command): bool
    {
        return array_key_exists(get_class($command), $this->handlers);
    }

    private function getCommandHandler(CommandInterface $command)
    {
        if ($this->hasCommandHandler($command))
            return $this->container->make($this->handlers[get_class($command)]);

        $handlerClass = $command::class . 'Handler';
        if (class_exists($handlerClass))
            return $this->container->make($handlerClass);

        return null;
    }

    private function getCommandMiddlewares($commandHandler): array
    {
        $middlewares = [];
        foreach ($this->handlersMiddlewares as $interface => $middleware) {
            if ($commandHandler instanceof $interface)
                $middlewares[] = $middleware;
        }

        return $middlewares;
    }

    private function terminateMiddlewares($command, $middlewares)
    {
        foreach ($middlewares as $middleware) {
            if (method_exists($middleware, 'terminate'))
                $middleware->terminate($command);
        }
    }
}
