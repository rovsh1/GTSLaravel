<?php

namespace App\Hotel\View\Components;

use Illuminate\View\Component;

class Icon extends Component
{
    protected static array $aliases = [
        'filter' => 'filter_alt',
        'address' => 'map',
        'administration' => 'settings',
        'client' => 'person',
        'finance' => 'payments',
        'supplier' => 'group',
        'hotel' => 'hotel',
        'data' => 'database',
        'reports' => 'bar_chart',
        'booking' => 'airplane_ticket',
        'site' => 'language',
        'horizon' => 'layers',
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