<?php

namespace Module\Shared\Application\Support\CompanyRequisite;

use Module\Shared\Application\Entity\CompanyRequisite\CompanyRequisiteInterface;
use Module\Shared\Infrastructure\Models\CompanyRequisite as Model;
use Sdk\Module\Services\NamespaceReader;

class CompanyRequisiteLoader
{
    public function load(): array
    {
        $values = $this->getRequisitesValues();
        $constants = [];

        foreach ($this->getAvailableRequisites() as $class) {
            $constant = new $class();
            if (isset($values[$constant->key()])) {
                $constant->setValue($values[$constant->key()]);
            }
            $constants[] = $constant;
        }

        return $constants;
    }

    private function getRequisitesValues(): array
    {
        $values = [];
        $q = Model::query();
        foreach ($q->cursor() as $r) {
            $values[$r->key] = $r->value;
        }

        return $values;
    }

    private function getAvailableRequisites(): array
    {
        $classes = (new NamespaceReader(
            'Module\Shared\Application\Entity\CompanyRequisite',
            modules_path('Shared/Application/Entity/CompanyRequisite')
        ))->read();

        return array_filter($classes, fn($cls) => is_subclass_of($cls, CompanyRequisiteInterface::class));
    }
}