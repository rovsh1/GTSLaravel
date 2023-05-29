<?php

namespace Sdk\Module\Bus;

use Sdk\Module\Container\Container;
use Sdk\Module\Contracts\Bus\CommandInterface;
use Sdk\Module\Contracts\Validation\ValidatorPipelineBehaviorInterface;

class ValidatorPipelineBehavior implements ValidatorPipelineBehaviorInterface
{

    public function __construct(private readonly Container $container) { }

    public function validateCommand(\Sdk\Module\Contracts\Bus\CommandInterface $command): void
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
