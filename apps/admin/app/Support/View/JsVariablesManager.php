<?php

namespace App\Admin\Support\View;

class JsVariablesManager
{
    private array $data = [];

    public function add(string|array $name, mixed $value = null): static
    {
        if (is_array($name)) {
            foreach ($name as $k => $v) {
                $this->data[$k] = $v;
            }
        } else {
            $this->data[$name] = $value;
        }

        return $this;
    }

    public function render(): string
    {
        return '<script type="application/json" id="app-data">' . json_encode($this->data) . '</script>';
    }
}
