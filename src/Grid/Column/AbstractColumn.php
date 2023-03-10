<?php

namespace Gsdk\Grid\Column;

abstract class AbstractColumn implements ColumnInterface
{
    private static array $defaultOptions = [
        'order' => false,
        'text' => '',
        'class' => '',
        'renderer' => null,
        'params' => null,
        'emptyText' => ''
    ];

    protected array $options = [
        'renderer' => false
    ];

    public function __set($name, $value)
    {
        $this->setOption($name, $value);
    }

    public function __get($name)
    {
        if (isset($this->options[$name])) {
            return $this->options[$name];
        }
        //if (isset(self::$_default[$name])) return self::$_default[$name];
        return null;
    }

    public function __construct($name, $options = [])
    {
        if (!isset($options['id'])) {
            $options['id'] = 'grid_column_' . $name;
        }

        $options['type'] = strtolower((new \ReflectionClass($this))->getShortName());

        $this->setName($name)
            ->setOptions(array_merge(self::$defaultOptions, $this->options, $options));

        $this->init();
    }

    public function setName($name): static
    {
        $this->options['name'] = $name;
        return $this;
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

    public function formatValue($value, $row = null)
    {
        return $value;
    }

    public function prepareValue($value)
    {
        return $value;
    }

    protected function init() {}
}
