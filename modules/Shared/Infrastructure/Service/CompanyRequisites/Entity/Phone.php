<?php

namespace Module\Shared\Infrastructure\Service\CompanyRequisites\Entity;

use Module\Shared\Infrastructure\Service\CompanyRequisites\AbstractCompanyRequisite;

final class Phone extends AbstractCompanyRequisite implements CompanyRequisiteInterface
{
    protected string $cast = 'phone';

    public function name(): string
    {
        return 'Телефон';
    }

    public function default(): string
    {
        return '+998781209012';
    }
}
