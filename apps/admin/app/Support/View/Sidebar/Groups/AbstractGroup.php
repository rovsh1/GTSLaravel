<?php

namespace App\Admin\Support\View\Sidebar\Groups;

use App\Admin\Components\Prototype\PrototypeGroups;

abstract class AbstractGroup
{
    protected array $prototypes = [];

    public function __construct()
    {
        $this->build();
    }

    abstract protected function build(): void;

    abstract public function key(): string;

    public function title(): string
    {
        return __('sidebar.' . $this->key() . '-title');
    }

    protected function add(string $prototype)
    {
        $this->prototypes[] = app('prototypes')->get($prototype);
    }

    public function groups(): \Generator
    {
        foreach (PrototypeGroups::cases() as $case) {
            $group = new \stdClass();
            $group->items = $this->items($case);
            if (empty($group->items)) {
                continue;
            }
            $group->title = $case->value;
            yield $group;
        }
    }

    private function items(PrototypeGroups $group): array
    {
        $items = [];
        foreach ($this->prototypes as $prototype) {
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
