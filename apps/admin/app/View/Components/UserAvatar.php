<?php

namespace App\Admin\View\Components;

use App\Core\Contracts\File\FileInterface;
use Illuminate\View\Component;

class UserAvatar extends Component
{
    private string $defaultSrc = '/images/default-avatar.png';

    public function __construct(private readonly ?FileInterface $file) {}

    public function render(): string
    {
        return '<img src="' . ($this->file ? $this->file->url() : $this->defaultSrc) . '"/>';
    }
}
