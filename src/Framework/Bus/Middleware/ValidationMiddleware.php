<?php

namespace Custom\Framework\Bus\Middleware;

use Closure;
use Custom\Framework\Bus\CommandInterface;
use Illuminate\Contracts\Container\Container;

class ValidationMiddleware
{

    public function __construct(private readonly Container $container) { }

    public function handle(CommandInterface $command, Closure $next)
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
        if (!class_exists($validatorClass))
            return null;

        return $this->container->make($validatorClass);
    }
}
