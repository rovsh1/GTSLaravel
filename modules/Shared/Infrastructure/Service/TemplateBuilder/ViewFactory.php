<?php

declare(strict_types=1);

namespace Module\Shared\Infrastructure\Service\TemplateBuilder;

use Illuminate\Contracts\View\Factory;
use Module\Shared\Domain\Service\TemplateBuilder\ViewFactoryInterface;

class ViewFactory implements ViewFactoryInterface
{
    public function __construct(
        private readonly Factory $factory
    ) {}

    public function file($path, $data = [], $mergeData = [])
    {
        return $this->factory->file($path, $data, $mergeData);
    }
}
