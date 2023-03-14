<?php

namespace App\Admin\Support\View;

use Gsdk\Meta\Meta;
use Gsdk\Meta\MetaTags;
use Illuminate\Support\Facades\App;
use Illuminate\Contracts\View\View;

class Layout
{
    private string $version = 'v1.0';

    private MetaTags $meta;

    private string $view;

    private array $viewData = [];

    private array $options = [];

    public function __construct()
    {
        $this->meta = Meta::getFacadeRoot();
    }

    public function __call(string $name, array $arguments)
    {
        $this->meta->$name(...$arguments);
        return $this;
    }

    public function data(array $data): static
    {
        $this->viewData = array_merge($this->viewData, $data);
        return $this;
    }

    public function addMetaVariable(string $name, mixed $value): static
    {
        $this->meta->addMetaName($name, htmlspecialchars(json_encode($value)));
        return $this;
    }

    public function h1(string $h1): static
    {
        return $this->setOption('h1', $h1);
    }

    public function script(string $src): static
    {
        return $this->setOption('script', $src);
    }

    public function style(string $src): static
    {
        return $this->setOption('style', $src);
    }

    public function ss(string $src): static
    {
        return $this->setOption('style', $src)->setOption('script', $src);
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

    private function setOption(string $name, $value): static
    {
        $this->options[$name] = $value;
        return $this;
    }

    private function getViewData(): array
    {
        $data = array_merge($this->viewData, $this->options);
        $data['layout'] = $this;
        $data['title'] = $this->options['h1'] ?? $this->meta->getTitle();

        return $data;
    }

    private function configure(): void
    {
        $this->addDefaultMeta();

        $this->bootStyles();
    }

    private function addDefaultMeta(): void
    {
        $this->meta
            ->addLinkRel('icon', '/favicon.ico')
            ->addMetaHttpEquiv('Content-Type', 'text/html; charset=utf-8')
            ->addMetaHttpEquiv('X-UA-Compatible', 'IE=edge,chrome=1')
            ->addMetaHttpEquiv('Content-language', App::currentLocale())
            ->addMetaName('viewport', 'width=device-width, initial-scale=1')
            ->addMetaName('csrf-token', csrf_token());
    }

    private function bootStyles(): void
    {
        $style = $this->options['style'] ?? 'main';

        $this->meta
            ->addScript(($this->options['script'] ?? $style ?? 'main') . '.js?' . $this->version, ['defer' => true])
            ->addLinkRel('preload', '/css/' . $style . '.css?' . $this->version, ['as' => 'style'])
            ->addStyle($style . '.css?' . $this->version);
        //$this->head->addStyle('print.css?' . $this->version);
    }
}
