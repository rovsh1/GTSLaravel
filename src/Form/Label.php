<?php

namespace Gsdk\Form;

class Label
{
    protected static array $defaultOptions = [];

    public static function setDefaults(array $options): void
    {
        static::$defaultOptions = $options;
    }

    protected array $options = [
        'requiredLabel' => '',
        'class' => 'form-label',
    ];

    protected ?ElementInterface $element;

    public function __construct($options = [])
    {
        $this->setOptions(array_merge(static::$defaultOptions, $options));
    }

    public function __set($name, $value)
    {
        $this->setOption($name, $value);
    }

    public function __get($name)
    {
        return $this->options[$name] ?? null;
    }

    public function setOptions($options): static
    {
        foreach ($options as $k => $v) {
            $this->setOption($k, $v);
        }
        return $this;
    }

    public function setOption($key, $option): static
    {
        $this->options[$key] = $option;
        return $this;
    }

    public function setElement(ElementInterface $element): static
    {
        $this->element = $element;
        return $this;
    }

    public function render(): string
    {
        return '<label'
            . ' for="' . ($this->for ?? $this->element->getInputId()) . '"'
            . ($this->class ? ' class="' . $this->class . '"' : '')
            . '>'
            . $this->text
//            . ($this->requiredLabel && $this->element && $this->element->required ? ' <span class="required-label">' . $this->requiredLabel . '</span>' : '')
            . '</label>';
    }

    public function __toString(): string
    {
        return $this->render();
    }
}
