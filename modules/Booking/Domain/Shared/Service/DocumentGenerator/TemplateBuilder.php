<?php

namespace Module\Booking\Domain\Shared\Service\DocumentGenerator;

use Illuminate\Contracts\View\View;

class TemplateBuilder
{
    private array $attributes = [];

    public function __construct(
        private readonly string $file
    ) {}

    public function generate(): string
    {
        $builder = (new DocumentBuilder())
            ->template(
                $this->getTemplateView($this->file)->render()
            );

        $this->prepare($builder);

        return $builder->generate();
    }

    public function template(string $template): static
    {
        $this->template = $template;

        return $this;
    }

    public function attributes(array $attributes): static
    {
        $this->attributes = $attributes;

        return $this;
    }

    protected function prepare($builder): void
    {
        $builder
            ->image('logo', $this->getTemplateContents('company-logo-small.png'))
            ->image('stamp', $this->getTemplateContents('company-stamp-with-sign.png'))
            ->image('stamp_only', $this->getTemplateContents('company-stamp-without-sign.png'));
    }

    private function getTemplateContents(string $file): false|string
    {
        $path = storage_path("app/public/{$file}");

        return file_get_contents($path);
    }

    private function getTemplateView(string $file): View
    {
        return view($file, $this->attributes);
    }
}
