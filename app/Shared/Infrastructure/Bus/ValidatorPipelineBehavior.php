<?php

namespace GTS\Shared\Infrastructure\Bus;

use Illuminate\Contracts\Container\Container;

use GTS\Shared\Application\Command\CommandInterface;
use GTS\Shared\Application\Validation\ValidatorPipelineBehaviorInterface;

class ValidatorPipelineBehavior implements ValidatorPipelineBehaviorInterface
{

    public function __construct(private readonly Container $container) { }

    public function validateCommand(CommandInterface $command): void
    {
        /*$commandValidator = $this->getCommandValidator($command);
        if (!$commandValidator)
            return;

        $result = $commandValidator->validate($command);
        if ($result->isValid())
            return;

        $result->throwExceptions();*/
    }

    private function getCommandValidator(CommandInterface $command)
    {
        $validatorClass = $command::class . 'Validator';
        if (!class_exists($validatorClass))
            return null;

        return $this->container->make($validatorClass);
    }
}
