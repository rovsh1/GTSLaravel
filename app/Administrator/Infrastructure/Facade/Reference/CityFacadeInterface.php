<?php

namespace GTS\Administrator\Infrastructure\Facade\Reference;

interface CityFacadeInterface
{
    public function search(mixed $params = null);

    public function count(mixed $params = null): int;
}
