<?php

namespace Module\Shared\Infrastructure\Repository;

use Module\Shared\Domain\Constant as ConstantsNamespace;
use Module\Shared\Domain\Constant\ConstantInterface;
use Module\Shared\Domain\Repository\ConstantRepositoryInterface;
use Module\Shared\Infrastructure\Models\Constant;

class ConstantRepository implements ConstantRepositoryInterface
{
    public function findByKey(string $key): ConstantInterface
    {
        $cls = ConstantsNamespace::class . '\\' . $key;
        if (!class_exists($cls)) {
            throw new \Exception('Constant [' . $key . '] undefined');
        }

        $value = Constant::where('key', $key)
            ->whereEnabled()
            ->value('value');

        return new $cls($value);
    }

    public function getConstantValue(string $key): mixed
    {
        return $this->findByKey($key)?->value() ?? null;
    }
}
