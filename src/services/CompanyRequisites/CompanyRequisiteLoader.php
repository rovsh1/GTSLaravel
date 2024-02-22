<?php

namespace Services\CompanyRequisites;

use Sdk\Module\Services\NamespaceReader;
use Services\CompanyRequisites\Entity\CompanyRequisiteInterface;
use Shared\Models\CompanyRequisite as Model;

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
            'Services\CompanyRequisites\Entity',
            __DIR__ . '/Entity'
        ))->read();

        return array_filter($classes, fn($cls) => is_subclass_of($cls, CompanyRequisiteInterface::class));
    }
}
