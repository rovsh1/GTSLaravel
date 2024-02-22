<?php

namespace App\Admin\View\Components\UI;

use App\Admin\Support\Facades\Layout;
use Illuminate\View\Component;

class ContentTitle extends Component
{
    public function __construct(
        private readonly ?string $addBtnUrl = null,
        private readonly ?string $addBtnText = null,
        private readonly string $addBtnType = 'button',
    ) {
    }

    public function render(): string
    {
        $html = '<div class="content-header">';
        $html .= '<div class="title">' . Layout::getTitle() . '</div>';
        if ($this->addBtnUrl) {
            $inner = '<i class="icon">add</i>' . ($this->addBtnText ?? 'Добавить');
            if ($this->addBtnType === 'button') {
                $html .= '<button type="button" class="btn btn-add" data-url="' . $this->addBtnUrl . '">'
                    . $inner . '</button>';
            } else {
                $html .= '<a class="btn btn-add" href="' . $this->addBtnUrl . '">' . $inner . '</a>';
            }
        }

        $html .= '</div>';

        return $html;
    }
}
