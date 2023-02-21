<?php

namespace GTS\Administrator\Infrastructure\Repository;

use App\Admin\Models\Administrator;
use GTS\Administrator\Domain\Entity\Administrator as AdministratorEntity;
use GTS\Administrator\Domain\Factory\AdministratorFactory;
use GTS\Administrator\Domain\Repository\AdministratorRepositoryInterface;

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
