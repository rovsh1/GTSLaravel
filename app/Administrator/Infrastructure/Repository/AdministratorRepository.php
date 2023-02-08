<?php

namespace GTS\Administrator\Infrastructure\Repository;

use GTS\Administrator\Domain\Repository\AdministratorRepositoryInterface;
use GTS\Administrator\Domain\Factory\AdministratorFactory;
use GTS\Administrator\Infrastructure\Models\Administrator;
use GTS\Administrator\Domain\Entity\Administrator as AdministratorEntity;

class AdministratorRepository implements AdministratorRepositoryInterface
{
    public function findByLogin(string $login): ?AdministratorEntity
    {
        $administrator = Administrator::findByLogin($login);

        if (!$administrator) {
            return null;
        }

        return AdministratorFactory::fromArray($administrator->toArray());
    }
}
