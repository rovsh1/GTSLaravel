<?php

namespace Gsdk\Grid\Column;

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
        $html .= '<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>';
        $html .= '<div class="dropdown-menu">';

        foreach ($this->options['actions'] as $action) {
            $html .= match ($action) {
                'edit' => '<a class="dropdown-item" href="' . $this->options['route'] . '/' . $row->id . '/edit"><i class="bx bx-edit-alt me-1"></i> Редактировать</a>',
                'delete' => '<a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Удалить</a>'
            };
        }

        $html .= '</div></div>';

        return $html;
    }
}
