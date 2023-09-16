<?php

namespace App\Admin\View\Components;

use Illuminate\View\Component;
use Module\Shared\Dto\FileDto;

class FileImage extends Component
{
    public function __construct(private readonly ?FileDto $file) {}

    public function render(): string
    {
        return $this->file
            ? '<img src="' . $this->file->url . '"/>'
            : '';
    }
}
