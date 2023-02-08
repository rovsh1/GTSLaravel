<?php

namespace Gsdk\Form\Element;

use Gsdk\Form\Support\Element\SelectItems;

class Select extends AbstractElement
{
    protected array $options = [
        'inputType' => 'url'
    ];

    private SelectItems $items;

    public function __construct(string $name, array $options = [])
    {
        $this->items = new SelectItems($options);

        if (isset($options['items'])) {
            $this->items->add($options['items']);
            unset($options['items']);
        } elseif (isset($options['enum'])) {
            $this->items->fromEnum($options['enum']);
        }

        parent::__construct($name, $options);
    }

    public function getHtml(): string
    {
        return '';
    }
}
