<?php

namespace Gsdk\Meta\Head;

interface MetaTagInterface
{
    public function getHtml(): string;

    public function getIdentifier(): ?string;

    public function getAttribute(string $name);
}
