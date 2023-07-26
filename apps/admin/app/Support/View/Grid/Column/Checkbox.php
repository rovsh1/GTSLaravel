<?php

namespace App\Admin\Support\View\Grid\Column;

use Gsdk\Grid\Column\AbstractColumn;

class Checkbox extends AbstractColumn
{
    protected array $options = [
        'checkboxClass' => null,
        'dataAttributeName' => 'id',
    ];

    public function formatValue($value, $row = null)
    {
        return "<input class='form-check-input {$this->options['checkboxClass']}' type='checkbox' data-{$this->options['dataAttributeName']}='{$row['id']}'>";
    }
}
