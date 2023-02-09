<?php

namespace GTS\Administrator\Infrastructure\Facade\Reference;

interface CurrencyFacadeInterface
{
    public function search(mixed $params = null);

    public function count(mixed $params = null): int;
}
