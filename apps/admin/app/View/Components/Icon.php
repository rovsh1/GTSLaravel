<?php

namespace App\Admin\View\Components;

use Illuminate\View\Component;

class Icon extends Component
{
    protected static array $aliases = [
        'filter' => 'filter_alt',
        'address' => 'map',
        'administration' => 'settings',
        'client' => 'person',
        'finance' => 'payments',
        'hotel' => 'hotel',
        'data' => 'database',
        'reports' => 'bar_chart',
        'reservation' => 'airplane_ticket',
        'site' => 'language',
    ];

    protected readonly ?string $key;

    public function __construct(string|null $key)
    {
        $this->key = static::$aliases[$key] ?? $key;
    }

    public function render(): string
    {
        return $this->key ? '<i class="icon">' . $this->key . '</i>' : '';
    }
}
