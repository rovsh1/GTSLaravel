<?php

namespace App\Admin\Providers;

use App\Admin\Support\Facades\Format;
use App\Admin\Support\Format\ContactRule;
use App\Admin\Support\Format\PriceRule;
use Gsdk\Format\FormatServiceProvider as ServiceProvider;

class FormatServiceProvider extends ServiceProvider
{
    protected $formats = [
        'id' => 'ND=7;NGS=;NLZ=1',
        'price' => 'NFD=2;NDS=,;NGS= ;',
        'number' => 'ND=7;NFD=;NGS= ;',
        'boolean' => 'BT=Да;BF=Нет',
        //'filesize' => 'FU=байт,Кб,Мб,Гб;FFD=1;FDS=,;FGS= ;'
    ];

    protected $rules = [
        'contact' => ContactRule::class,
        'price' => PriceRule::class
    ];

    public function register()
    {
        parent::register();

        class_alias(Format::class, 'Format');
    }
}
