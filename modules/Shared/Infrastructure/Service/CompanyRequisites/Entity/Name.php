<?php

namespace Module\Shared\Infrastructure\Service\CompanyRequisites\Entity;

use Module\Shared\Infrastructure\Service\CompanyRequisites\AbstractCompanyRequisite;

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
