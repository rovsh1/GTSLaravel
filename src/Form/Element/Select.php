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

    private array $attributes = ['size', 'multiple', 'required', 'autofocus'];

    public function __construct(string $name, array $options = [])
    {
        if (isset($options['items'])) {
            $this->setItems($options['items']);
            unset($options['items']);
        }

        if (isset($options['groups'])) {
            $this->setGroups($options['groups']);
            unset($options['groups']);
        }

        if (isset($options['enum'])) {
            //$this->setItems($options['items']);
        }

        parent::__construct($name, $options);
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
        foreach ($items as $data) {
            $this->items[] = (new OptionBuilder($data))
                ->setValue($this->valueIndex)
                ->setText($this->textIndex)
                ->setGroup($this->groupIndex)
                ->get();
        }
    }

    public function isSelected($value): bool
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
        if ($value === $this->emptyItemValue) {
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
        $html = '<select ' . (new InputAttributes($this))->render($this->attributes) . '>';

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
