<?php

namespace Gsdk\Form\Support\SelectBox;

class OptgroupBuilder
{
    private static array $labelKeys = ['text', 'name', 'presentation'];

    private static array $idKeys = ['key', 'value'];

    protected mixed $data;

    private \stdClass $group;

    public function __construct(mixed $data)
    {
        if (is_array($data)) {
            $this->data = (object)$data;
        } elseif (is_scalar($data)) {
            $this->data = (object)['id' => $data, 'label' => $data];
        } elseif (!is_object($data)) {
            throw new \Exception('Group format invalid');
        } else {
            $this->data = $data;
        }

        $this->group = new \stdClass();

        $this->setParam('id', self::$idKeys);

        $this->setParam('label', self::$labelKeys);
    }

    public function get(): \stdClass
    {
        return $this->group;
    }

    protected function setParam($key, $autoKeys = []): static
    {
        $this->group->$key = null;

        if (isset($this->data->$key)) {
            $this->group->$key = $this->data->$key;
        } else {
            foreach ($autoKeys as $k) {
                if (isset($this->data->$k) && !empty($this->data->$k)) {
                    $this->group->$key = $this->data->$k;
                    break;
                }
            }
        }

        return $this;
    }
}
