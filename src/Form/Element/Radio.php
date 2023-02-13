<?php

namespace Gsdk\Form\Element;

use Gsdk\Form\Support\Radio\ItemBuilder;

class Radio extends AbstractElement
{
    protected array $options = [
        'itemClass' => 'radio-item',
        'inputClass' => '',
        'labelClass' => '',
        'valueIndex' => 'id',
        'textIndex' => 'name',
        'multiple' => false,
        'inline' => true
    ];

    private array $items = [];

    public function __construct(string $name, array $options = [])
    {
        if (isset($options['items'])) {
            $this->setItems($options['items']);
            unset($options['items']);
        } elseif (isset($options['enum'])) {
            $this->items = ItemBuilder::fromEnum($options['enum']);
        }

        parent::__construct($name, $options);
    }

    public function setItems(iterable $items): void
    {
        foreach ($items as $data) {
            $this->items[] = (new ItemBuilder($data))
                ->setValue($this->valueIndex)
                ->setText($this->textIndex)
                ->get();
        }
    }

    public function isChecked($value): bool
    {
        $selected = $this->getValue();

        if (!$this->multiple) {
            return ($value === $selected);
        } elseif (is_array($selected)) {
            return in_array($value, $selected);
        } else {
            return false;
        }
    }

    public function valueExists($value): bool
    {
        foreach ($this->items as $item) {
            if ($item->value === $value) {
                return true;
            }
        }
        return false;
    }

    protected function prepareValue($value)
    {
        if ($this->multiple) {
            if (!is_iterable($value)) {
                return [];
            }

            $values = [];
            foreach ($value as $val) {
                if (is_object($val)) {
                    $val = $val->id;
                }

                if ($this->valueExists($val)) {
                    $values[] = $val;
                }
            }
            return $values;
        } else {
            return $value;
        }
    }

    public function getHtml(): string
    {
        $html = '<div class="radio-items">';
        if ($this->multiple) {
            $inputName = $this->getInputName() . '[]';
            $html .= implode('', array_map(fn($r) => $this->getInputHtml('checkbox', $r, $inputName), $this->items));
        } else {
            $inputName = $this->getInputName();
            $html .= implode('', array_map(fn($r) => $this->getInputHtml('radio', $r, $inputName), $this->items));
        }

        $html .= '</div>';

        return $html;
    }

    private function getInputHtml($type, $item, $inputName): string
    {
        $inputId = $this->getInputId() . '_' . $item->value;

        return '<div class="' . $this->itemClass . '">'
            . '<input type="' . $type . '"'
            . ' class="' . $this->inputClass . '"'
            . ' value="' . htmlspecialchars($item->value) . '"'
            . ' name="' . $inputName . '"'
            . ' id="' . $inputId . '"'
            . ($this->isChecked($item->value) ? ' checked' : '') . '>'
            . '<label class="' . $this->labelClass . '" for="' . $inputId . '">' . $item->text . '</label>'
            . '</div>';
    }
}
