<?php

namespace App\Hotel\Providers;

use App\Hotel\Support\Facades\Format;
use Gsdk\Format\FormatServiceProvider as ServiceProvider;

class FormatServiceProvider extends ServiceProvider
{
    protected $formats = [
        'id' => 'ND=7;NGS=;NLZ=1',
        'price' => 'NFD=2;NDS=,;NGS= ;',
        'number' => 'ND=7;NFD=;NGS= ;',
        'boolean' => 'BT=Да;BF=Нет',
        'time' => 'H:i',
        //'filesize' => 'FU=байт,Кб,Мб,Гб;FFD=1;FDS=,;FGS= ;'
    ];

    protected $rules = [
    ];

    public function register()
    {
        parent::register();

        class_alias(Format::class, 'Format');
    }
}
