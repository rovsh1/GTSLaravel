<?php

namespace Module\Booking\Common\Domain\Service\DocumentGenerator;

use Illuminate\Contracts\View\View;

class TemplateBuilder
{
    private array $attributes = [];

    public function __construct(
        private readonly string $templatesPath,
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
            ->image('logo', $this->getTemplateContents('logo.png'))
            ->image('stamp', $this->getTemplateContents('stamp.png'))
            ->image('stamp_only', $this->getTemplateContents('stamp_only.png'));
    }

    private function getTemplateContents(string $file): string
    {
        return file_get_contents($this->templatesPath . DIRECTORY_SEPARATOR . $file);
    }

    private function getTemplateView(string $file): View
    {
        return view($file, $this->attributes);
    }
}
