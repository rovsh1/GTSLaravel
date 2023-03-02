<?php

namespace App\Admin\View\Navigation;

class Breadcrumbs extends \Gsdk\Navigation\Breadcrumbs
{
    protected function boot()
    {
        $this
            ->view('layouts.breadcrumbs')
            ->addHome(route('home'), 'Dashboard');
    }
}
