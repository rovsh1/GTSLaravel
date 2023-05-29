<?php

namespace Sdk\Module\Foundation\Support\EntityFactory;

use Illuminate\Database\Eloquent\Collection;

interface FactoryInterface
{
    public function createFrom(mixed $data);

    public function createCollectionFrom(Collection|array $items): array;
}
