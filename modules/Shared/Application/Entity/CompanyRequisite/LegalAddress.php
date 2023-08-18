<?php

namespace Module\Shared\Application\Entity\CompanyRequisite;

use Module\Shared\Application\Support\CompanyRequisite\AbstractCompanyRequisite;

final class LegalAddress extends AbstractCompanyRequisite implements CompanyRequisiteInterface
{
    protected string $cast = 'address';

    public function name(): string
    {
        return 'Юр. Адрес компании';
    }

    public function default(): string
    {
        return 'Узбекистан, г.Ташкент, 100015, ул. Кичик Бешагач, д. 104А';
    }
}
