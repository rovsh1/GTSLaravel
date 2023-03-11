<?php

namespace Module\Shared\Domain\Constant;

abstract class AbstractConstant implements ConstantInterface
{
    protected string $cast = 'string';

    protected mixed $value;

    protected mixed $default = null;

    public function __construct(?string $value = null)
    {
        $this->value = $this->castValue($value ?? $this->default);
    }

    public function key(): string
    {
        return (new \ReflectionClass($this))->getShortName();
    }

    public function value(): mixed
    {
        return $this->value;
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
