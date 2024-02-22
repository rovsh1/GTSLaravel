<?php

namespace App\Hotel\Providers;

use App\Hotel\Support\Facades\Format;
use App\Hotel\Support\Format\ContactRule;
use App\Hotel\Support\Format\DistanceRule;
use App\Hotel\Support\Format\EnumRule;
use App\Hotel\Support\Format\PeriodRule;
use App\Hotel\Support\Format\PriceRule;
use App\Hotel\Support\Format\TimeRule;
use Gsdk\Format\FormatServiceProvider as ServiceProvider;

class FormatServiceProvider extends ServiceProvider
{
    protected $formats = [
        'id' => 'ND=7;NGS=;NLZ=1',
        'price' => 'NFD=2;NDS=,;NGS= ;',
        'number' => 'ND=7;NFD=;NGS= ;',
        'boolean' => 'BT=Да;BF=Нет',
        'date' => 'd.m.Y',
        'datetime' => 'd.m.Y H:i',
        'time' => 'H:i',
        //'filesize' => 'FU=байт,Кб,Мб,Гб;FFD=1;FDS=,;FGS= ;'
    ];

    protected $rules = [
        'contact' => ContactRule::class,
        'price' => PriceRule::class,
        'distance' => DistanceRule::class,
        'period' => PeriodRule::class,
        'enum' => EnumRule::class,
        'time' => TimeRule::class,
    ];

    public function register()
    {
        parent::register();

        class_alias(Format::class, 'Format');
    }
}
