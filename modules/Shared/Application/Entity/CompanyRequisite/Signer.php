<?php

namespace Module\Shared\Application\Entity\CompanyRequisite;

use Module\Shared\Application\Support\CompanyRequisite\AbstractCompanyRequisite;

final class Signer extends AbstractCompanyRequisite implements CompanyRequisiteInterface
{
    public function name(): string
    {
        return 'Подписант';
    }

    public function default(): string
    {
        return 'Заитов А.А.';
    }
}
