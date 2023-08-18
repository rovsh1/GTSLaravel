<?php

namespace Module\Shared\Application\Support\Constant;

abstract class AbstractConstant
{
    protected string $cast = 'string';

    protected mixed $value;

    public function __construct(?string $value = null)
    {
        $this->value = $this->castValue($value ?? $this->default());
    }

    abstract public function name(): string;

    public function key(): string
    {
        return (new \ReflectionClass(static::class))->getShortName();
    }

    public function value(): mixed
    {
        return $this->value;
    }

    public function setValue(mixed $value): void
    {
        $this->value = $this->castValue($value);
    }

    public function default(): mixed
    {
        return null;
    }

    public function cast(): string
    {
        return $this->cast;
    }

    protected function castValue(?string $value): mixed
    {
        if (is_null($value)) {
            return null;
        }

        return match ($this->cast) {
            'int' => (int)$value,
            'float' => (float)$value,
            'bool' => (bool)$value,
            default => $value,
        };
    }
}
