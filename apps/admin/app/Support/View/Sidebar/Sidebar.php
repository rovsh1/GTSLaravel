<?php

namespace App\Admin\Support\View\Sidebar;

class Sidebar
{
    public function render()
    {
        return view('layouts/main/sidebar', [
            'sidebars' => $this->sidebars()
        ]);
    }

    private function sidebars(): \Generator
    {
        $sidebars = [
            Groups\Reservations::class
        ];

        foreach ($sidebars as $cls) {
            $sidebar = new $cls();

            $dto = new \stdClass();
            $dto->title = $sidebar->title();
            $dto->groups = $sidebar->groups();

            yield $dto;
        }
    }
}
