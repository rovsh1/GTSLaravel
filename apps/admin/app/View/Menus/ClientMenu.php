<?php

namespace App\Admin\View\Menus;

use App\Admin\Support\View\Sidebar\AbstractSubmenu;
use Module\Shared\Enum\Client\TypeEnum;

class ClientMenu extends AbstractSubmenu
{
    public function __construct(private $model, string $current = 'info')
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
            ->addUrl('info', route('client.show', $this->model), 'Описание', ['icon' => 'description']);

        if ($this->model->type === TypeEnum::LEGAL_ENTITY) {
            $this->addUrl('legals', route('client.legals.index', $this->model), 'Юр. лица', ['icon' => 'gavel']);
        }

        $this->addUrl('documents', route('client.show', $this->model), 'Документы', ['icon' => 'pending_actions'])
            ->addUrl('users', route('client.show', $this->model), 'Пользователи', ['icon' => 'person']);
    }
}
