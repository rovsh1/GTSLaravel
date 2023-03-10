<?php

namespace App\Admin\Components\Sidebar\Menu;

class Item implements ItemInterface
{
    public readonly string $key;

    public readonly string $url;

    public readonly string $text;

    public function __construct(array $options)
    {
        $this->key = $options['key'];
        $this->url = $options['url'];
        $this->text = $options['text'];
    }
}
