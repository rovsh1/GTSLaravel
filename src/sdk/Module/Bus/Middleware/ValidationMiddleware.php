<?php

namespace Sdk\Module\Bus\Middleware;

use Sdk\Module\Container\Container;
use Sdk\Module\Contracts\Bus\CommandInterface;

class ValidationMiddleware
{
    public function __construct(private readonly Container $container) {}

    public function handle(CommandInterface $command, \Closure $next)
    {
        $commandValidator = $this->getCommandValidator($command);
        if ($commandValidator) {
            $result = $commandValidator->validate($command);
            //if (!$result->isValid())
            //    $result->throwExceptions();
        }

        return $next($command);
    }

    private function getCommandValidator(CommandInterface $command)
    {
        $validatorClass = $command::class . 'Validator';
        if (!class_exists($validatorClass)) {
            return null;
        }

        return $this->container->make($validatorClass);
    }
}
