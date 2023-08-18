<?php

namespace Module\Shared\Application\Support\Constant;

use Module\Shared\Application\Entity\Constant\ConstantInterface;
use Module\Shared\Infrastructure\Models\Constant as Model;
use Sdk\Module\Services\NamespaceReader;

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
            'Module\Shared\Application\Entity\Constant',
            modules_path('Shared/Application/Entity/Constant')
        ))->read();

        return array_filter($classes, fn($cls) => is_subclass_of($cls, ConstantInterface::class));
    }
}