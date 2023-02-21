<?php

namespace App\Admin\Http\View;

use Gsdk\ViewLayout\Layout as Base;

class Layout extends Base
{
    //protected $menuNamespace = Navigation::class;

    public function bodyClass($style): static
    {
        return $this->setOption('bodyClass', $style);
    }

    protected function configure()
    {
        parent::configure();

        $data = [];
        //'homeUrl' => route('home'),
        //$data['base_url'] = base_url();
        //$data['user'] = ['id' => auth()->id()];
        //$data['languages'] = app('languages')->map(fn($l) => ['name' => $l->name, 'code' => $l->code]);

        $this->addAppData($data);

        $this->head->addStyle(asset('assets/vendor/fonts/boxicons.css'))
            ->addLinkRel('preconnect', 'https://fonts.googleapis.com')
            ->addLinkRel('preconnect', 'https://fonts.gstatic.com')
            ->addStyle('https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap')
            ->addStyle(asset('assets/vendor/css/core.css'))
            ->addStyle(asset('assets/vendor/css/theme-default.css'))
            ->addStyle(asset('assets/css/demo.css'))
            ->addStyle(asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css'))
            ->addScript(asset('assets/vendor/js/helpers.js'))
            ->addScript(asset('assets/js/config.js'));
    }
}
