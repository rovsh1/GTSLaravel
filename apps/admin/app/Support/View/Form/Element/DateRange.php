<?php

namespace App\Admin\Support\View\Form\Element;

use Carbon\Carbon;
use Gsdk\Form\Element\Text;

class DateRange extends Text
{
    private const DELIMETER = ' - ';

    protected array $options = [
        'inputType' => 'text',
        'maxValue' => null,
        'minValue' => null,
        'format' => 'd.m.Y',
        'autocomplete' => 'off',
        'emptyValue' => false
    ];

    public function __construct(string $name, array $options = [])
    {
        parent::__construct($name, $options);
        $this->class .= ' daterange';
    }

    public function getValue()
    {
        return $this->prepareValue($this->value);
    }

    protected function prepareValue($value)
    {
        if (is_string($value) && !empty($value)) {
            $dates = explode(self::DELIMETER, $value);
            return [
                'valueFrom' => $dates[0] ? new Carbon($dates[0]) : null,
                'valueTo' => (isset($dates[1]) && $dates[1]) ? new Carbon($dates[1]) : null
            ];
        }
        return null;
    }
}
