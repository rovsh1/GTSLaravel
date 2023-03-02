<?php

namespace App\Core\Components\Locale;

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

    public function current()
    {
        return $this->languages[$this->position];
    }

    public function next()
    {
        $this->position++;
    }

    public function key()
    {
        return $this->position;
    }

    public function valid()
    {
        return isset($this->languages[$this->position]);
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function count()
    {
        return count($this->languages);
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

    public function get(string $code)
    {
        foreach ($this->languages as $language) {
            if ($language->code === $code) {
                return $language;
            }
        }

        throw new \OutOfRangeException('Language "' . $code . '" undefined');
    }

    public function map(callable $fn)
    {
        return array_map($fn, $this->languages);
    }

    public function default()
    {
        foreach ($this->languages as $language) {
            if ($language->isDefault()) {
                return $language;
            }
        }

        return null;
    }
}
