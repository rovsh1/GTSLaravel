<?php

namespace Module\Shared\Application\Entity\CompanyRequisite;

use Module\Shared\Application\Support\CompanyRequisite\AbstractCompanyRequisite;

final class Name extends AbstractCompanyRequisite implements CompanyRequisiteInterface
{
    public function name(): string
    {
        return 'Компания';
    }

    public function default(): string
    {
        return 'ООО «GOTOSTANS»';
    }
}
