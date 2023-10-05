<?php

namespace App\Admin\View\Menus;

use App\Admin\Support\View\Sidebar\AbstractSubmenu;

class MarkupGroupMenu extends AbstractSubmenu
{
    public function __construct(private $model, string $current = 'rules')
    {
        parent::__construct((string)$model, $current);

        $this->build();
    }

    public function title(): string
    {
        return '<div class="name">' . $this->title . '</div>'
            . '<div class="hint">ID: ' . $this->model->id . '</div>';
    }

    private function build()
    {
        $this
            ->addUrl('rules', route('markup-group.rules.index', $this->model), 'Наценки отелей', ['icon' => 'currency_ruble'])
            ->addUrl('clients', route('markup-group.clients.index', $this->model), 'Клиенты', ['icon' => 'person']);
    }
}
