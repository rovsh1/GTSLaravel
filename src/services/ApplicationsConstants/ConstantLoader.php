<?php

namespace Services\ApplicationsConstants;

use Sdk\Module\Services\NamespaceReader;
use Services\ApplicationsConstants\Constant\ConstantInterface;
use Shared\Models\Constant as Model;

class ConstantLoader
{
    public function load(): array
    {
        $values = $this->getConstantValues();
        $constants = [];

        foreach ($this->getAvailableConstants() as $class) {
            $constant = new $class();
            if (isset($values[$constant->key()])) {
                $constant->setValue($values[$constant->key()]);
            }
            $constants[] = $constant;
        }

        return $constants;
    }

    private function getConstantValues(): array
    {
        $values = [];
        $q = Model::query();
        foreach ($q->cursor() as $r) {
            $values[$r->key] = $r->value;
        }

        return $values;
    }

    private function getAvailableConstants(): array
    {
        $classes = (new NamespaceReader(
            'Services\ApplicationsConstants\Constant',
            __DIR__ . '/Constant'
        ))->read();

        return array_filter($classes, fn($cls) => is_subclass_of($cls, ConstantInterface::class));
    }
}
