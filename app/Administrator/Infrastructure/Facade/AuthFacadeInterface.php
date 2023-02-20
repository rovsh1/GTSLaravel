<?php

namespace GTS\Administrator\Infrastructure\Facade;

interface AuthFacadeInterface
{
    public function login(string $login, string $password);
}
