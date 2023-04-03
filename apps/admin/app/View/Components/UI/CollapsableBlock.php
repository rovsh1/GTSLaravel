<?php

namespace App\Admin\View\Components\UI;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class CollapsableBlock extends Component
{
    public function __construct(
        public string  $header,
        public ?string $id = null,
    )
    {
        if ($id === null) {
            $this->id = Str::random(10);
        }
    }

    public function render(): View
    {
        return view('_partials.components.collapsable-block');
    }
}
