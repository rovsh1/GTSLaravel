<?php

namespace GTS\Shared\Infrastructure\Bus;

use Illuminate\Contracts\Container\Container;
use GTS\Shared\Application\Command\CommandBusInterface;
use GTS\Shared\Application\Command\CommandInterface;
use GTS\Shared\Application\Validation\ValidatorPipelineBehaviorInterface;

class CommandBus implements CommandBusInterface
{

    private array $handlers = [];

    public function __construct(
        private readonly Container $container,
        private readonly ValidatorPipelineBehaviorInterface $validator
    ) {
    }

    public function subscribe(string $commandClassName, string $handlerClassName)
    {
        $this->handlers[$commandClassName] = $handlerClassName;
    }

    public function execute(CommandInterface $command): mixed
    {
        /*foreach ($this->middlewareHandlers as $middlewareHandler) {
            $middlewareHandler($command);
        }*/

        $commandHandler = $this->getCommandHandler($command);
        if (!$commandHandler)
            throw new \Exception('Command [' . $command::class . '] handler undefined');

        $this->validator->validateCommand($command);

        return $commandHandler->handle($command);
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
}
