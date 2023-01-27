<?php

namespace GTS\Shared\Infrastructure\Bus\Middleware;

use Closure;
use Illuminate\Contracts\Container\Container;

use GTS\Shared\Application\Command\CommandInterface;

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
