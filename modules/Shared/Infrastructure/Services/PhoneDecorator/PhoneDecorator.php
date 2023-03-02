<?php

namespace Module\Shared\Infrastructure\Services\PhoneDecorator;

class PhoneDecorator
{
    private ?string $original;

    private string $value = '';

    private Format\FormatInterface $format;

    public function __construct(
        ?string $original,
        ?string $country = null
    ) {
        $this->original = $original;

        if ($original) {
            $this->value = trim(preg_replace('/[^0-9]/', '', $original));
        }

        $this->setCountry($country);
    }

    public function setCountry(?string $country): void
    {
        if ($country) {
            $cls = __NAMESPACE__ . '\\Format\\' . ucfirst($country);
            if (class_exists($cls)) {
                $this->format = new $cls($this);
            } else {
                $this->format = new Format\DefaultFormat($this);
            }
            //throw new \Exception('Phone country format not found');
        } else {
            $this->format = new Format\DefaultFormat($this);
        }
    }

    public function getOriginal(): ?string
    {
        return $this->original;
    }

    public function getCountryCode(): ?string
    {
        return $this->format->getCountryCode($this->value);
    }

    public function getAreaCode(): ?string
    {
        return $this->format->getAreaCode($this->value);
    }

    public function getNumber(): ?string
    {
        return $this->format->getNumber($this->value);
    }

    public function toValue(): ?string
    {
        return $this->value;
    }

    public function toContact(): string
    {
        return '+' . $this->value;
    }

    public function toText(): string
    {
        return $this->format->toText($this->value);
    }

    public function toHtml(): string
    {
        return '<a href="tel:' . $this->toContact() . '" class="phone">' . $this->toText() . '</a>';
    }

    public function __toString()
    {
        return $this->toText();
    }
}
