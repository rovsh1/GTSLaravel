<?php

namespace App\Admin\Support\Http;

abstract class AbstractAction
{
    protected $view;

    protected $title;

    public function handle()
    {
        $this->boot();

        $this->bootBreadcrumbs();

        $this->bootMenu();

        $this->bootLayout();

        return $this->render();
    }

    protected function render()
    {
        return app('layout')
            ->title($this->title())
            ->view($this->view, $this->getViewData());
    }

    protected function title()
    {
        return $this->title;
    }

    protected function getViewData()
    {
        return [];
    }

    protected function boot() {}

    protected function bootLayout() {}

    protected function bootMenu() {}

    protected function bootBreadcrumbs() {}
}
