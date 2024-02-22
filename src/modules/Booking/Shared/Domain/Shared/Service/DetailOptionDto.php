<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Shared\Service;

use Format;

class DetailOptionDto
{
    public readonly string $label;

    private const DATE_TYPE = 'date';
    private const TIME_TYPE = 'time';
    private const TEXT_TYPE = 'text';
    private const PRICE_TYPE = 'price';
    private const NUMBER_TYPE = 'number';

    public function __construct(
        string $label,
        public readonly string $type,
        public readonly mixed $value,
    ) {
        $this->label = __($label);
    }

    public static function createDate(string $label, mixed $value): static
    {
        return new static($label, self::DATE_TYPE, $value);
    }

    public static function createTime(string $label, mixed $value): static
    {
        return new static($label, self::TIME_TYPE, $value);
    }

    public static function createPrice(string $label, mixed $value): static
    {
        return new static($label, self::PRICE_TYPE, $value);
    }

    public static function createNumber(string $label, mixed $value): static
    {
        return new static($label, self::NUMBER_TYPE, $value);
    }

    public static function createText(string $label, mixed $value): static
    {
        return new static($label, self::TEXT_TYPE, $value);
    }

    public function getHumanValue(): ?string
    {
        return match ($this->type) {
            self::DATE_TYPE => Format::date($this->value, 'd.m.Y'),
            self::TIME_TYPE => Format::time($this->value),
            self::PRICE_TYPE => Format::price($this->value),
            self::NUMBER_TYPE => Format::number($this->value),
            default => $this->value
        };
    }
}
