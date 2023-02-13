<?php

namespace Gsdk\Form\Concerns;

trait HasOptions
{
    protected static array $defaultOptions = [];

    protected static array $defaultMessages = [];

    protected static array $elementsDefaults = [
        'required' => false,
        'nullable' => false,
        'disabled' => false,
        'readable' => true,
        'render' => true,
        'rules' => null, //validation rules
        'requiredText' => ''
    ];

//    protected array $options = [];

    public static function setDefaults(array $options): void
    {
        self::$defaultOptions = $options;
    }

    public static function setElementDefaults(array $options): void
    {
        self::$elementsDefaults = array_merge(self::$elementsDefaults, $options);
    }

    public static function setDefaultMessages(array $messages): void
    {
        self::$defaultMessages = $messages;
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

    public function getOption($key)
    {
        return $this->options[$key] ?? null;
    }

    public static function mergeElementOptions($staticOptions, $defaultOptions, $options): array
    {
        return array_merge(self::$elementsDefaults, $staticOptions, $defaultOptions, $options);
    }

    protected function mergeOptions(array $options): void
    {
        $this->setOptions(array_merge(self::$defaultOptions, $this->options, $options));
    }
}
