<?php

namespace App\Core\Contracts\File;

interface FileInterface
{
    public function guid(): string;

    public function entityId(): ?int;

    public function name(): ?string;

    public function url(): string;
}