<?php

namespace App\Admin\Support\View\Sidebar;

use App\Admin\Components\Prototype\PrototypeGroups;

class Sidebar
{
    public function render()
    {
        return view('layouts/dashboard/sidebar', [
            'groups' => $this->getGroups()
        ]);
    }

    private function getGroups(): \Generator
    {
        foreach (PrototypeGroups::cases() as $case) {
            $group = new \stdClass();
            $group->items = $this->getItems($case);
            if (empty($group->items)) {
                continue;
            }
            $group->title = $case->value;
            yield $group;
        }
    }

    private function getItems(PrototypeGroups $group): array
    {
        $items = [];
        foreach (app('prototypes')->all() as $prototype) {
            if ($prototype->group !== $group) {
                continue;
            }

            $item = new \stdClass();
            $item->text = $prototype->title();
            $item->href = $prototype->route();
            $items[] = $item;
        }
        return $items;
    }
}
