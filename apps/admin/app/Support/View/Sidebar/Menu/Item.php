<?php

namespace App\Admin\Support\View\Sidebar\Menu;

class Item implements ItemInterface
{
    public readonly string $key;

    public readonly string $url;

    public readonly string $text;

    public readonly ?string $icon;

    public function __construct(array $options)
    {
        $this->key = $options['key'];
        $this->url = $options['url'];
        $this->text = $options['text'];
        $this->icon = $options['icon'] ?? null;
    }
}
