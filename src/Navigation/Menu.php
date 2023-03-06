<?php

namespace Gsdk\Navigation;

class Menu
{

    protected static array $itemAttributes = ['id', 'title', 'target'];

    protected array $items = [];

    protected $current;

    protected array $options = [];

    public function __construct(array $options = [])
    {
        $this->options = array_merge($this->options, $options);
        $this->build();
    }

    public function view($view): static
    {
        return $this->setOption('view', $view);
    }

    public function setOption(string $key, $value): static
    {
        $this->options[$key] = $value;
        return $this;
    }

    public function getOption(string $key, $default = null)
    {
        return $this->options[$key] ?? $default;
    }

    public function current($current = null): static
    {
        $this->current = $current ?? request()->route()->getName();

        return $this;
    }

    public function add($params): static
    {
        $this->items[] = $this->itemFactory($params);
        return $this;
    }

    public function addUrl($url, $params): static
    {
        if (is_string($params)) {
            $params = ['text' => $params];
        }

        return $this->add(array_merge($params, ['url' => $url]));
    }

    public function addRoute($route, $params): static
    {
        if (is_string($params)) {
            $params = ['text' => $params];
        }

        return $this->add(
            array_merge($params, [
                'id' => $route,
                'url' => route($route)
            ])
        );
    }

    public function hr(): static
    {
        $this->items[] = '-';

        return $this;
    }

    public function items(): array
    {
        return $this->items;
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function render(): string
    {
        if ($this->isEmpty()) {
            return '';
        }

        if (!empty($this->options['view'])) {
            return (string)view($this->options['view'], $this->getViewData());
        }

        $menu = '<nav class="' . $this->getOption('class') . '">';
        $menu .= $this->renderItems();
        $menu .= '</nav>';

        return $menu;
    }

    public function __toString(): string
    {
        return $this->render();
    }

    protected function build() {}

    protected function getViewData(): array
    {
        return [
            'menu' => $this,
            'items' => $this->items
        ];
    }

    protected function itemFactory($params): \stdClass
    {
        $item = new \stdClass();
        foreach (static::$itemAttributes as $k) {
            $item->$k = $params[$k] ?? null;
        }
        $item->key = $params['key'] ?? $item->id;
        $item->url = $params['href'] ?? $params['url'] ?? '#';
        $item->icon = $params['icon'] ?? '';
        $item->text = $params['text'] ?? '';
        $item->class = $params['class'] ?? $params['cls'] ?? null;

        return $item;
    }

    protected function renderItems(): string
    {
        $menu = '';
        foreach ($this->items as $item) {
            if ($item === '-') {
                $menu .= $this->renderHr();
            } else {
                $menu .= $this->renderItem($item);
            }
        }

        return $menu;
    }

    protected function renderHr(): string
    {
        return '<hr>';
    }

    protected function renderItem($item): string
    {
        $cls = [];
        if ($item->class) {
            $cls[] = $item->class;
        }

        if ($this->current === $item->key) {
            $cls[] = 'current';
        }

        $html = '<a href="' . $item->url . '"';
        foreach (static::$itemAttributes as $k) {
            if ($item->$k) {
                $html .= ' ' . $k . '="' . $item->$k . '"';
            }
        }

        if ($cls) {
            $html .= ' class="' . implode(' ', $cls) . '"';
        }

        $html .= '>';
        $html .= $item->icon;
        $html .= $item->text;
        $html .= '</a>';

        return $html;
    }

}
