<?php

declare(strict_types=1);

namespace App\Admin\Models\Supplier;

use Illuminate\Contracts\Support\Arrayable;

class ServiceSettingsField
{
    private const SELECT_TYPE = 'select';
    private const BOOL_TYPE = 'bool';

    public function __construct(
        public readonly string $name,
        public readonly string $type,
        public readonly mixed $value,
        public readonly array|Arrayable|null $items = null
    ) {
    }

    public static function createSelect(string $name, mixed $value, array|Arrayable $items): static
    {
        return new static($name, self::SELECT_TYPE, $value, $items);
    }

    public static function createBool(string $name, mixed $value): static
    {
        return new static($name, self::BOOL_TYPE, $value);
    }
}