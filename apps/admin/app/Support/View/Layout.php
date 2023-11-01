<?php

namespace App\Admin\Support\View;

use Gsdk\Meta\Meta;
use Gsdk\Meta\MetaCollection;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;

class Layout
{
    private MetaCollection $meta;

    private string $view;

    private array $viewData = [];

    private array $options = [];

    private bool $configured = false;

    public function __construct()
    {
        $this->meta = Meta::collect();
    }

    public function __call(string $name, array $arguments)
    {
        $this->meta->$name(...$arguments);

        return $this;
    }

    public function __get(string $name)
    {
        return $this->get($name);
    }

    public function get(string $name)
    {
        return $this->viewData[$name] ?? null;
    }

    public function data(array $data): static
    {
        $this->viewData = array_merge($this->viewData, $data);

        return $this;
    }

    public function addMetaVariable(string $name, mixed $value): static
    {
        $this->meta->add(Meta::metaName($name, htmlspecialchars(is_string($value) ? $value : json_encode($value))));

        return $this;
    }

    public function title(string $title): static
    {
        $this->meta->title($title);

        return $this->setOption('title', $title);
    }

    public function getTitle(): ?string
    {
        return $this->options['title'] ?? null;
    }

    public function h1(string $h1): static
    {
        return $this->setOption('h1', $h1);
    }

    public function view(string $view, array $data = []): static
    {
        $this->view = $view;

        return $this->data($data);
    }

    public function render(): View
    {
        $this->configure();

        return view($this->view, $this->getViewData());
    }

    public function renderMeta(): string
    {
        return $this->meta->toHtml();
    }

    public function configure(): void
    {
        if ($this->configured) {
            return;
        }

        $this->configured = true;

        $this->addDefaultMeta();
    }

    private function setOption(string $name, $value): static
    {
        $this->options[$name] = $value;

        return $this;
    }

    private function getViewData(): array
    {
        $data = array_merge($this->viewData, $this->options);
        $data['layout'] = $this;
        $data['title'] ??= $this->options['h1'];// ?? $this->meta->getTitle();

        return $data;
    }

    private function addDefaultMeta(): void
    {
        $this->meta
            ->icon('/favicon.ico')
            ->contentType('text/html; charset=utf-8')
            ->xUACompatible('IE=edge,chrome=1')
            ->contentLanguage(App::currentLocale())
            ->viewport('width=device-width, initial-scale=1')
            ->add(Meta::metaName('google-maps-key', env('GOOGLE_MAPS_API_KEY')));
    }
}
