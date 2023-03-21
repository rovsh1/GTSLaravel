<?php

namespace App\Admin\View\Components;

use App\Core\Contracts\File\FileInterface;
use Illuminate\View\Component;

class FileImage extends Component
{
    public function __construct(private readonly ?FileInterface $file) {}

    public function render(): string
    {
        return $this->file
            ? '<img src="' . $this->file->url() . '"/>'
            : '';
    }
}
