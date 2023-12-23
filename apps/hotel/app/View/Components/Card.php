<?php

namespace App\Hotel\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Card extends Component
{
    public function __construct(
        public string  $header,
        public bool    $collapsable = false,
        public ?string $id = null,
    )
    {
        if ($id === null) {
            $this->id = Str::random(10);
        }
    }

    public function render(): View
    {
        return view('_partials.components.card');
    }
}
