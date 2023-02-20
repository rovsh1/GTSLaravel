<?php

namespace GTS\Administrator\Infrastructure\Facade;

use Custom\Framework\Contracts\Bus\CommandBusInterface;

use GTS\Administrator\Application\Command\LoginByPassword;

class AuthFacade implements AuthFacadeInterface
{
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {}

    public function login(string $login, string $password)
    {
        return $this->commandBus->execute(new LoginByPassword($login, $password));
    }
}
