<?php

namespace App\Admin\Support\View\Navigation;

class Breadcrumbs extends \Gsdk\Navigation\Breadcrumbs
{
    protected function build()
    {
        $this
            ->view('layouts.ui.breadcrumbs')
            ->addHome(route('home'), 'Dashboard');
    }
}
