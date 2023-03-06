<?php

namespace App\Admin\Support\View;

use Illuminate\Support\Facades\App;

class Layout
{
    private string $version = 'v1.0';

    private $meta;

    private array $options = [];

    public function __construct()
    {
        $this->meta = app('meta');
    }

    public function __call(string $name, array $arguments)
    {
        $this->meta->$name(...$arguments);
        return $this;
    }

    public function setOption(string $name, $value): static
    {
        $this->options[$name] = $value;
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

    public function view(string $view, array $data = [])
    {
        $this->configure();

        return view($view, array_merge($data, $this->getViewData()));
    }

    private function getViewData(): array
    {
        $data = $this->options;
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
        $style = $this->options['style'] ?? 'dashboard';

        $this->meta
            ->addScript(($this->options['script'] ?? $style ?? 'dashboard') . '.js?' . $this->version, ['defer' => true])
            ->addLinkRel('preload', '/css/' . $style . '.css?' . $this->version, ['as' => 'style'])
            ->addStyle($style . '.css?' . $this->version);
        //$this->head->addStyle('print.css?' . $this->version);
    }
}
