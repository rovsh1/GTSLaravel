<?php

namespace Gsdk\Form\Support\SelectBox;

class OptionBuilder
{
    private static array $textKeys = ['text', 'name', 'presentation'];

    private static array $valueKeys = ['id', 'key', 'value'];

    private static array $groupKeys = ['parent_id', 'group_id'];

    private \stdClass $data;

    private \stdClass $option;

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
        return $this->setParam('value', $key, self::$valueKeys);
    }

    public function setText($key): static
    {
        $this->setParam('text', $key, self::$textKeys);

        if (!isset($this->option->text)) {
            $this->option->text = $this->option->value;
        }

        return $this;
    }

    public function setGroup($key): static
    {
        return $this->setParam('groupId', $key, self::$groupKeys);
    }

    public function get(): \stdClass
    {
        return $this->option;
    }

    protected function setParam($key, $defaultKey, $autoKeys = []): static
    {
        $this->option->$key = null;

        if (isset($this->data->$key)) {
            $this->option->$key = $this->data->$key;
        } elseif (isset($this->data->$defaultKey)) {
            $this->option->$key = $this->data->$defaultKey;
        } else {
            foreach ($autoKeys as $k) {
                if (isset($this->data->$k)) {
                    $this->option->$key = $this->data->$k;
                    break;
                }
            }
        }

        return $this;
    }
}
