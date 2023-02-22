<?php

namespace App\Admin\Services\Acl;

class Permission
{
    public function __construct(
        public readonly string $id,
    ) {}
}
