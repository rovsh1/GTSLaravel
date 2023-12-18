<?php

namespace App\Hotel\Support\View\Form\Element;

use App\Admin\Support\Facades\Format;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Gsdk\Form\Element\Text;
use Gsdk\Form\Support\Element\InputAttributes;

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

    public function getValue(): ?CarbonPeriod
    {
        return $this->prepareValue($this->value);
    }

    public function getHtml(): string
    {
        return '<input'
            . ' type="' . ($this->inputType ?? 'text') . '"'
            . ' class="' . ($this->class ?? 'input-' . $this->inputType) . '"'
            . (new InputAttributes($this))->render($this->attributes)
            . ' value="' . self::escape($this->getFrontValue()) . '">';
    }

    /**
     * @param string|array|CarbonPeriod $value
     * @return CarbonPeriod|null
     */
    protected function prepareValue($value): ?CarbonPeriod
    {
        if (empty($value)) {
            return null;
        }
        if ($value instanceof CarbonPeriod) {
            return $value;
        }
        $dates = [];
        if (is_array($value)) {
            $dates = $value;
        }
        if (is_string($value)) {
            $dates = explode(self::DELIMETER, $value);
        }
        if (!empty($dates)) {
            $dateFrom = $dates[0] ? new Carbon($dates[0]) : null;
            $dateTo = (isset($dates[1]) && $dates[1]) ? new Carbon($dates[1]) : null;
            if ($dateFrom === null && $dateTo === null) {
                return null;
            }
            return new CarbonPeriod($dateFrom, $dateTo);
        }
        return null;
    }

    private function getFrontValue(): ?string
    {
        return Format::period($this->getValue());
    }
}
