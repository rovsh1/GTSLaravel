<?php

namespace GTS\Shared\Domain\Adapter;

interface RequestBuilderInterface
{
    public function build(): self|RequestInterface;
}
