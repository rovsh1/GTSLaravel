<?php

namespace App\Admin\Support\View\Sidebar\Groups;

class Reservations extends AbstractGroup
{
    public function key(): string
    {
        return 'reservations';
    }

    protected function build(): void
    {
        $this->add('reference.country');
        $this->add('reference.city');
    }
}
