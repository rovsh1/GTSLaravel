<?php

namespace Gsdk\Form\Element;

use Gsdk\Form\Support\Element\InputAttributes;
use Gsdk\Form\Support\SelectBox\OptgroupBuilder;
use Gsdk\Form\Support\SelectBox\OptionBuilder;

class Select extends AbstractElement
{
    protected array $options = [
        'valueIndex' => 'id',
        'textIndex' => 'name',
        'groupIndex' => '',
        'emptyItem' => null,
        'emptyValue' => null,
        'emptyItemValue' => ''
    ];

    private array $groups = [];

    private array $items = [];

    private array $attributes = ['required', 'readonly', 'disabled', 'size', 'multiple', 'autofocus'];

    public function __construct(string $name, array $options = [])
    {
        $groups = $options['groups'] ?? [];
        unset($options['groups']);

        $items = $options['items'] ?? [];
        unset($options['items']);

        parent::__construct($name, $options);

        $this->setGroups($groups);
        $this->setItems($items);
    }

    public function setGroups(iterable $groups): void
    {
        $this->groups = [];

        foreach ($groups as $data) {
            $this->groups[] = (new OptgroupBuilder($data))->get();
        }
    }

    public function setItems(iterable $items): void
    {
        foreach ($items as $k => $data) {
            $this->items[] = (new OptionBuilder($k, $data))
                ->setValue($this->valueIndex)
                ->setText($this->textIndex)
                ->setGroup($this->groupIndex)
                ->get();
        }
    }

    public function isSelected($value): bool
    {
        $selected = $this->getValue();
        $item = $this->getItem($value);
        if ($selected === null || !$item) {
            return false;
        }

        if (!$this->multiple) {
            return ($item->originalValue === $selected);
        } elseif (is_array($selected)) {
            return in_array($item->originalValue, $selected);
        } else {
            return false;
        }
    }

    public function getInputName(): string
    {
        return parent::getInputName() . ($this->multiple ? '[]' : '');
    }

    private function getItem($value): ?\stdClass
    {
        $optionValue = OptionBuilder::formatValue($value);
        foreach ($this->items as $item) {
            if ($item->originalValue === $value || $optionValue === $item->value) {
                return $item;
            }
        }
        return null;
    }

    protected function prepareValue($value)
    {
        if (null === $value) {
            return $this->multiple ? [] : null;
        } elseif ($value === $this->emptyItemValue) {
            return $this->emptyValue;
        }

        if ($this->multiple) {
            if (!is_iterable($value)) {
                return [];
            }

            $values = [];
            foreach ($value as $val) {
                if (is_object($val)) {
                    $val = $val->id;
                }

                if ($r = $this->getItem($val)) {
                    $values[] = $r->originalValue;
                }
            }
            return $values;
        } else {
            return $this->getItem($value)?->originalValue;
        }
    }

    public function getHtml(): string
    {
        $html = '<select class="form-select ' . ($this->class ?? '') . '" ' . (new InputAttributes($this))->render(
                $this->attributes
            ) . '>';

        if (null !== $this->emptyItem) {
            $html .= '<option value="' . $this->emptyItemValue . '">' . $this->emptyItem . '</option>';
        }

        if ($this->groups) {
            foreach ($this->items as $item) {
                if ($item->groupId === null) {
                    $html .= $this->getItemHtml($item);
                }
            }

            foreach ($this->groups as $group) {
                $html .= $this->getGroupHtml($group);
            }
        } else {
            $html .= implode('', array_map(fn($r) => $this->getItemHtml($r), $this->items));
        }

        $html .= '</select>';

        return $html;
    }

    private function getGroupHtml($group): string
    {
        $html = '<optgroup label="' . $group->label . '">';
        foreach ($this->items as $item) {
            if ($item->groupId === $group->id) {
                $html .= $this->getItemHtml($item);
            }
        }
        return $html;
    }

    private function getItemHtml($item): string
    {
        return '<option value="' . htmlspecialchars($item->value) . '"'
            //. ($item->attributes ? ' ' . $this->attributes : '')
            . ($this->isSelected($item->value) ? ' selected' : '')
            . '>' . $item->text . '</option>';
    }
}
