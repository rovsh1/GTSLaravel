<?php

namespace App\Admin\View\Components;

class CategoryIcon extends Icon
{
    protected static array $aliases = [
        'administration' => 'settings',
        'client' => 'person',
        'finance' => 'payments',
        'hotel' => 'hotel',
        'data' => 'database',
        'reports' => 'bar_chart',
        'reservation' => 'airplane_ticket',
        'site' => 'language',
    ];
}
