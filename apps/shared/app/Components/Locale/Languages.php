<?php

namespace App\Shared\Components\Locale;

class Languages implements \Countable, \Iterator
{
    private array $languages = [];

    private int $position = 0;

    public function __construct(array $languagesConfigs)
    {
        foreach ($languagesConfigs as $code => $config) {
            $this->languages[] = new Language($code, $config);
        }
    }

    public function current(): Language
    {
        return $this->languages[$this->position];
    }

    public function next(): void
    {
        $this->position++;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->languages[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function count(): int
    {
        return count($this->languages);
    }

    public function all(): array
    {
        return $this->languages;
    }

    public function has(string $code): bool
    {
        foreach ($this->languages as $language) {
            if ($language->code === $code) {
                return true;
            }
        }

        return false;
    }

    public function get(string $code): Language
    {
        foreach ($this->languages as $language) {
            if ($language->code === $code) {
                return $language;
            }
        }

        throw new \OutOfRangeException('Language "' . $code . '" undefined');
    }

    public function map(callable $fn): array
    {
        return array_map($fn, $this->languages);
    }

    public function default(): ?Language
    {
        foreach ($this->languages as $language) {
            if ($language->isDefault()) {
                return $language;
            }
        }

        return null;
    }
}
