<?php

namespace App\Admin\View\Components;

use Illuminate\View\Component;

class Tab extends Component
{
    public function __construct(
        public string $target,
        public bool $active = false,
    ) {}

    public function render(): \Closure
    {
        return function ($data) {
            return '<li class="nav-item" role="presentation">'
                . '<button'
                . ' id="' . ($this->target . '-tab') . '"'
                . ' class="nav-link' . ($this->active ? ' active' : '') . '"'
                . ' data-bs-target="#' . $this->target . '"'
                . ' aria-controls="' . $this->target . '"'
                . ' data-bs-toggle="tab" type="button" role="tab"'
                . ($this->active ? ' aria-selected="true"' : '')
                . '>' . $data['slot'] . '</button>'
                . '</li>';
        };
    }
}
