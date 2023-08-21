<?php

declare(strict_types=1);

namespace Module\Shared\Domain\Service\TemplateBuilder;

interface ViewFactoryInterface
{
    public function file($path, $data = [], $mergeData = []);
}
