<?php

namespace Module\Booking\Common\Domain\Service\DocumentGenerator;

class TemplateBuilder
{
    private array $attributes = [];

    protected readonly string $templatesPath;

    public function __construct(private readonly string $name)
    {
        $this->templatesPath = __DIR__ . DIRECTORY_SEPARATOR . 'templates';
    }

    public function generate(): string
    {
        $builder = (new DocumentBuilder())
            ->template($this->getTemplateContents($this->name))
            ->attributes($this->attributes);

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
            ->image('logo', $this->getTemplateContents('logo.jpg'))
            ->image('stamp', $this->getTemplateContents('stamp.jpg'))
            ->image('stamp_only', $this->getTemplateContents('stamp_only.jpg'));
    }

    private function getTemplateContents(string $name): string
    {
        return file_get_contents($this->templatesPath . DIRECTORY_SEPARATOR . $name);
    }
}
