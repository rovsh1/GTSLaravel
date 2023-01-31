<?php

namespace GTS\Administrator\Infrastructure\Repository;

use GTS\Administrator\Domain\Repository\AclRepositoryInterface;
use GTS\Administrator\Infrastructure\Models\Access\Rule;

class AclRepository implements AclRepositoryInterface
{
    public function __construct() {}

    public function getAdministratorRules(int $id)
    {
        return Rule::whereAdministrator($id)
            ->addSelect([
                'administrator_access_rules.resource',
                'administrator_access_rules.permission',
                'administrator_access_rules.flag',
            ])
            ->get();
    }
}
