<?php

namespace App\Admin\Support\Http\Resources;

use App\Admin\View\Navigation\Breadcrumbs;
use Illuminate\Contracts\Support\Responsable;

abstract class AbstractLayoutResource implements Responsable
{
    protected string $title = '';

    protected string $view;

    protected array $viewData = [];

    public function __construct()
    {
        $this->boot();
    }

    public function title(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function view(string $view, array $data = []): static
    {
        $this->view = $view;
        $this->viewData = $data;
        return $this;
    }

    public function toResponse($request)
    {
        $this->build();

        return app('layout')
            ->title($this->title)
            ->view($this->view, array_merge($this->viewData, $this->getViewData()));
    }

    protected function boot() {}

    protected function build()
    {
        $this->breadcrumbs();
    }

    protected function getViewData(): array
    {
        return [];
    }

    protected function breadcrumbs(): Breadcrumbs
    {
        return app('breadcrumbs');
    }
}
