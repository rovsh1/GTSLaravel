<?php

namespace App\Admin\View\Components\UI;

use App\Admin\View\Components\Icon;
use Illuminate\View\Component;

class AddButton extends Component
{
    public function __construct(
        private readonly ?string $url,
        private readonly ?string $id = null,
    ) {}

    public function render(): \Closure
    {
        return function ($data) {
            if (!$this->url) {
                return '';
            }

            return '<a href="' . $this->url . '"'
                . ($this->id ? ' id="' . $this->id . '"' : '')
                . ' class="btn btn-add">'
                . (new Icon('add'))->render()
                . ($data['slot']->isEmpty() ? 'Добавить' : $data['slot'])
                . '</a>';
        };
    }
}
