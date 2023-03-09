<?php

namespace App\Admin\Support\View\Grid\Column;

use Gsdk\Grid\Column\AbstractColumn;

class Actions extends AbstractColumn
{
    protected array $options = [
        'actions' => ['edit', 'delete']
    ];

    public function renderer($row, $value): string
    {
        if (empty($this->options['actions'])) {
            return '';
        }

        $html = '<div class="dropdown">';
        $html .= '<button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"><i class="icon-dropdown-menu"></i></button>';
        $html .= '<div class="dropdown-menu">';

        foreach ($this->options['actions'] as $action) {
            $html .= match ($action) {
                'edit' => '<a class="dropdown-item" href="' . $this->options['route'] . '/' . $row->id . '/edit"><i class="icon-edit"></i> Редактировать</a>',
                'delete' => '<a class="dropdown-item" href="javascript:void(0);"><i class="icon-delete"></i> Удалить</a>'
            };
        }

        $html .= '</div></div>';

        return $html;
    }
}
