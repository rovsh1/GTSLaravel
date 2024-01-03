<?php

namespace Services\CompanyRequisites\Entity;

use Services\CompanyRequisites\AbstractCompanyRequisite;

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
