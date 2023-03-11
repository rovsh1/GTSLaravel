<?php

namespace App\Admin\Support\View\Components;

use Illuminate\View\Component;

class Icon extends Component
{
    protected static array $aliases = [
        'filter' => 'filter_alt'
    ];

    protected readonly string $key;

    public function __construct(string $key)
    {
        $this->key = static::$aliases[$key] ?? $key;
    }

    public function render(): string
    {
        return '<i class="icon">' . $this->key . '</i>';
    }
}
