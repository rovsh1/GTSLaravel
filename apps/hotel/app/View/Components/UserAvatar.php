<?php

namespace App\Hotel\View\Components;

use Illuminate\View\Component;
use Sdk\Shared\Dto\FileDto;

class UserAvatar extends Component
{
    private string $defaultSrc = '/images/default-avatar.png';

    public function __construct(private readonly ?FileDto $file)
    {
    }

    public function render(): \Closure
    {
        return function ($data) {
            return '<img src="' . ($this->file ? $this->file->url : $this->defaultSrc) . '" '
                . $data['attributes'] . '/>';
        };
    }
}
