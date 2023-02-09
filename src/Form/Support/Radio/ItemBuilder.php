<?php

namespace Gsdk\Form\Support\Radio;

class ItemBuilder
{
    private static array $textKeys = ['text', 'name', 'presentation'];

    private static array $valueKeys = ['id', 'key', 'value'];

    protected \stdClass $data;

    protected \stdClass $option;

    public static function fromEnum(string $enum): array
    {
        if (!enum_exists($enum)) {
            throw new \Exception('Enum undefined');
        }

        $items = [];
        foreach ($enum::cases() as $r) {
            $item = new \stdClass();
            $item->originalValue = $r;
            $item->value = $r->value ?? $r->name;
            $item->text = $r->name;
            $items[] = $item;
        }
        return $items;
    }

    public static function formatValue($value)
    {
        if (is_object($value) && enum_exists($value::class)) {
            return $value->value ?? $value->name;
        } else {
            return (string)$value;
        }
    }

    public function __construct(mixed $data)
    {
        if (is_array($data)) {
            $this->data = (object)$data;
        } elseif (is_scalar($data)) {
            $this->data = (object)['value' => $data, 'text' => $data];
        } elseif (!is_object($data)) {
            throw new \Exception('Item format invalid');
        } else {
            $this->data = $data;
        }

        $this->option = new \stdClass();
    }

    public function setValue($key): static
    {
        $this->option->originalValue = $this->find('value', $key, self::$valueKeys);
        $this->option->value = self::formatValue($this->option->originalValue);

        return $this;
    }

    public function setText($key): static
    {
        $this->option->text = $this->find('text', $key, self::$textKeys)
            ?? $this->option->value;

        return $this;
    }

    public function get(): \stdClass
    {
        return $this->option;
    }

    protected function find($key, $defaultKey, $autoKeys = [])
    {
        if (isset($this->data->$key)) {
            return $this->data->$key;
        } elseif (isset($this->data->$defaultKey)) {
            return $this->data->$defaultKey;
        } else {
            foreach ($autoKeys as $k) {
                if (isset($this->data->$k)) {
                    return $this->data->$k;
                }
            }
            return null;
        }
    }
}
