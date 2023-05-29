<?php

namespace Sdk\Module\Bus;

use Sdk\Module\Container\Container;
use Sdk\Module\Contracts\Bus\JobInterface;

class JobDispatcher implements \Sdk\Module\Contracts\Bus\JobDispatcherInterface
{
    private array $handlers = [];

    private array $handlersMiddlewares = [

    ];

    public function __construct(
        private readonly Container $container,
        private readonly array $middlewares = [],
    ) {}

    public function subscribe(string $jobClassName, string $handlerClassName)
    {
        $this->handlers[$jobClassName] = $handlerClassName;
    }

    public function dispatch(\Sdk\Module\Contracts\Bus\JobInterface $job): void {}

    public function execute(JobInterface $job): mixed
    {
        $jobHandler = $this->getJobHandler($job);
        if (!$jobHandler) {
            throw new \Exception('Command [' . $job::class . '] handler undefined');
        }

        $action = fn($job) => $jobHandler->handle($job);

        $middlewares = array_merge($this->getJobMiddlewares($jobHandler), $this->middlewares);
        //$middlewares = array_unique($middlewares);
        $middlewares = array_map(fn($middleware) => $this->container->make($middleware), $middlewares);
        foreach ($middlewares as $middleware) {
            $action = fn($command) => $middleware->handle($job, $action);
        }

        $result = $action($job);

        $this->terminateMiddlewares($job, $middlewares);

        //$events = $this->container->get(EventsPipelineInterface::class)->getEvents();
        //dispatch events

        return $result;
    }

    private function hasJobHandler(JobInterface $command): bool
    {
        return array_key_exists(get_class($command), $this->handlers);
    }

    private function getJobHandler(\Sdk\Module\Contracts\Bus\JobInterface $command)
    {
        if ($this->hasJobHandler($command)) {
            return $this->container->make($this->handlers[get_class($command)]);
        }

        $handlerClass = $command::class . 'Handler';
        if (class_exists($handlerClass)) {
            return $this->container->make($handlerClass);
        }

        return null;
    }

    private function getJobMiddlewares($commandHandler): array
    {
        $middlewares = [];
        foreach ($this->handlersMiddlewares as $interface => $middleware) {
            if ($commandHandler instanceof $interface) {
                $middlewares[] = $middleware;
            }
        }

        return $middlewares;
    }

    private function terminateMiddlewares($command, $middlewares)
    {
        foreach ($middlewares as $middleware) {
            if (method_exists($middleware, 'terminate')) {
                $middleware->terminate($command);
            }
        }
    }
}
