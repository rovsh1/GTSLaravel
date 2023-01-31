<?php

namespace GTS\Administrator\Domain\Service\Acl;

class Permission
{
    public function __construct(
        public readonly string $id,
    ) {}
}
