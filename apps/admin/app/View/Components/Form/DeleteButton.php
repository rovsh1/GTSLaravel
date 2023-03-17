<?php

namespace App\Admin\View\Components\Form;

use Illuminate\View\Component;

class DeleteButton extends Component
{
    public function __construct(
        private readonly ?string $url,
        private readonly string $text = 'Удалить',
    ) {}

    public function render()
    {
        if (!$this->url) {
            return '';
        }

        return '<button type="button" data-url="' . $this->url . '" data-form-action="delete" class="btn btn-delete">'
            . '<x-icon key="delete"/>'
            . $this->text
            . '</button>';
    }
}
