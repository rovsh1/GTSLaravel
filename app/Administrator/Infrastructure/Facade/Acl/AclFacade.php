<?php

namespace GTS\Administrator\Infrastructure\Facade\Reference;

use GTS\Administrator\Domain\Repository\AclRepositoryInterface;
use GTS\Administrator\Domain\Service\Acl\AccessControlInterface;

class AclFacade implements AclFacadeInterface
{
    public function __construct(
        public readonly AccessControlInterface $acl,
        public readonly AclRepositoryInterface $aclRepository,
    ) {}

    public function setAdministrator(int $id)
    {
        $aclRules = $this->acl->rules();

        $administratorRules = $this->aclRepository->getAdministratorRules($id);

        foreach ($administratorRules as $rule) {
            $aclRules->setPermission($rule->resource, $rule->permission, $rule->flag);
        }
    }
}
