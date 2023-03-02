<?php

namespace App\Core\Components\Locale;

class Language
{
    protected array $data;

    public function __construct($code, array $data)
    {
        $data['code'] = $code;
        $this->data = $data;
    }

    public function __isset(string $name): bool
    {
        return isset($this->data[$name]);
    }

    public function __get(string $name)
    {
        return $this->data[$name] ?? null;
    }

    public function isDefault(): bool
    {
        return isset($this->data['default']) && $this->data['default'];
    }
}
