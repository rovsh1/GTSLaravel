<?php

namespace App\Admin\Support\View\Navigation;

use App\Admin\Components\Factory\Prototype;

class Breadcrumbs extends \Gsdk\Navigation\Breadcrumbs
{
    private ?string $currentCategory = null;

    protected function build()
    {
        $this
            ->view('layouts.ui.breadcrumbs')//->addHome(route('home'), '<i class="icon">home</i>')
        ;
    }

    public function category(string $category): static
    {
        $this->currentCategory = $category;

        return $this->add(__('category.' . $category));
    }

    public function prototype(Prototype $prototype): static
    {
        if (!$this->currentCategory) {
            $this->category($prototype->config('category'));
        }

        return $this->addUrl($prototype->route('index'), $prototype->title('index') ?? 'Default index');
    }
}
