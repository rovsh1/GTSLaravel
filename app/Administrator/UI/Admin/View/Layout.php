<?php

namespace GTS\Administrator\UI\Admin\View;

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
    }

}
