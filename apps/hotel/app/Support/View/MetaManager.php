<?php

namespace App\Hotel\Support\View;

use Gsdk\Meta\Meta;
use Gsdk\Meta\MetaCollection;

class MetaManager
{
    private MetaCollection $collection;

    public function __construct()
    {
        $this->collection = Meta::collect();
    }

    public function __call(string $name, array $arguments)
    {
        $this->collection->$name(...$arguments);

        return $this;
    }

    public function toHtml(): string
    {
        return $this->collection->toHtml();
    }
}
