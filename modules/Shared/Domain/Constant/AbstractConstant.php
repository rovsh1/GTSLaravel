<?php

namespace Module\Shared\Domain\Constant;

abstract class AbstractConstant implements ConstantInterface
{
    protected string $cast = 'string';

    protected mixed $value;

    public function __construct(?string $value)
    {
        $this->value = $this->castValue($value);
    }

    public function key(): string
    {
        return (new \ReflectionClass($this))->getShortName();
    }

    public function value(): mixed
    {
        return $this->value;
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
