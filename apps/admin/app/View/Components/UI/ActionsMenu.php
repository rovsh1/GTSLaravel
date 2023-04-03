<?php

namespace App\Admin\View\Components\UI;

use Illuminate\View\Component;

class ActionsMenu extends Component
{
    public function __construct(
        private readonly ?string $editUrl,
        private readonly ?string $deleteUrl,
        private readonly ?string $id = null,
    ) {}

    public function render(): \Closure
    {
        $buttons = '';

        if ($this->editUrl !== null) {
            $buttons .= <<<HTML
                <li>
                    <a class="dropdown-item" href="{$this->editUrl}">
                        <i class="icon">edit</i>
                        Редактировать
                    </a>
                </li>
            HTML;
        }

        if ($this->deleteUrl) {
            $buttons .= <<<HTML
                <li>
                    <a class="dropdown-item" data-url="{$this->deleteUrl}" data-form-action="delete">
                        <i class="icon">delete</i>
                        Удалить
                    </a>
                </li>
            HTML;
        }

        if (empty($buttons)) {
            return fn($data) => '';
        }

        $elementId = $this->id ?? 'menu-actions';
        return fn($data) => <<<HTML
            <div class="dropdown menu-actions-wrapper">
                <div class="btn btn-menu dropdown-toggle" href="#" role="button" id="{$elementId}" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="icon">more_vert</i>
                </div>

              <ul class="dropdown-menu" aria-labelledby="{$elementId}">
                {$buttons}
              </ul>
            </div>
        HTML;
    }
}
