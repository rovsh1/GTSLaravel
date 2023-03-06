<?php

namespace App\Admin\Support\View\Sidebar;

class SidebarFactory
{
    private Sidebar $sidebar;

    public function __construct()
    {
        $this->sidebar = new Sidebar();
    }

    public function factory(): Sidebar
    {
        $this
            ->addHotel()
            ->addReservation()
            ->addSystem();

        return $this->sidebar;
    }

    private function addHotel(): static
    {
        $this->sidebar->addGroup(
            (new SidebarGroup('hotel', 'Отель'))
                ->addRoute('hotel.index', 'Отели')
        );

        return $this;
    }

    private function addReservation(): static
    {
        return $this;
    }

    private function addSystem(): static
    {
        return $this;
    }
}
