<?php

namespace App\Admin\Support\View\Navigation;

class GridActions
{
    private array $items = [];

    public function __construct(
        public readonly string $url,
    ) {}

    public function edit(string $text = 'Редактировать'): static
    {
        return $this->addItem('edit', 'edit', $text);
    }

    public function delete(string $text = 'Удалить'): static
    {
        return $this->addItem('delete', 'delete', $text);
    }

    public function render(int $id): string
    {
        $menu = '';

        if (isset($this->items['edit'])) {
            $menu .= $this->renderItem($this->items['edit']);
        }

        if (isset($this->items['delete'])) {
            $menu .= '<li><hr class="dropdown-divider"></li>';
            $menu .= $this->renderItem($this->items['delete']);
        }

        if (empty($menu)) {
            return '';
        }

        return '<div class="dropdown text-end">'
            . '<a href="#" class="btn-avatar" data-bs-toggle="dropdown" aria-expanded="false">'
            . '<div class="icon">more_vert</div>'
            . '</a>'
            . '<ul class="dropdown-menu text-small">'
            . $menu
            . '</ul>'
            . '</div>';
    }

    private function renderItem($item): string
    {
        return '<li><a class="dropdown-item" href="' . '' . '">'
            . '<div class="icon">' . $item->icon . '</div>'
            . $item->text
            . '</a></li>';
    }

    private function addItem(string $key, string $icon, string $text): static
    {
        $this->items[$key] = (object)[
            'icon' => $icon,
            'text' => $text,
        ];
        return $this;
    }
}
