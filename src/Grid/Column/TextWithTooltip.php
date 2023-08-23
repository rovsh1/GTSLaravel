<?php

namespace Gsdk\Grid\Column;

class TextWithTooltip extends AbstractColumn
{
    protected array $options = [
        'tooltip' => ''
    ];

    public function formatValue($value, $row = null)
    {
        $tooltip = $this->options['tooltip'];
        if (is_callable($tooltip)) {
            $tooltip = $tooltip($row, $value);
        }

        if (empty($value)) {
            return $value;
        }

        return "<a href='#' data-bs-toggle='tooltip' data-bs-html='true' data-bs-trigger='hover' data-bs-title='{$tooltip}'>{$value}</a>";
    }
}
