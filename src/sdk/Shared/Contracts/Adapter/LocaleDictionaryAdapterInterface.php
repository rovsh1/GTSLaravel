<?php

namespace Sdk\Shared\Contracts\Adapter;

interface LocaleDictionaryAdapterInterface
{
    public function translate(string $key, array $replace = []): string;
}