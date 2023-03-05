<?php

namespace App\Admin\Support\View\Navigation;

class Breadcrumbs extends \Gsdk\Navigation\Breadcrumbs
{
    protected function boot()
    {
        $this
            ->view('layouts.breadcrumbs')
            ->addHome(route('home'), 'Dashboard');
    }
}
