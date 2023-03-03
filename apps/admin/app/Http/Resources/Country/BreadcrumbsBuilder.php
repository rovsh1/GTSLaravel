<?php

namespace App\Admin\Http\Resources\Country;

use App\Admin\View\Navigation\Breadcrumbs;

class BreadcrumbsBuilder
{
    public function __construct(private readonly Settings $settings)
    {
        $this->boot();
    }

    protected function boot(): Breadcrumbs
    {
        return app('breadcrumbs')
            ->addUrl($this->settings->route('index'), $this->settings->title('index'));
    }
}
