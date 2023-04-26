<?php

namespace App\Admin\Support\Facades;

use Carbon\CarbonPeriod;
use Gsdk\Format\Format as Facade;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static string contact(Model $contact, string $format = null)
 * @method static string price(mixed $price, string $format = null)
 * @method static string number(mixed $value, string $format = null)
 * @method static string date(mixed $value, string $format = null)
 * @method static string boolean(mixed $value, string $format = null)
 * @method static string fileSie(mixed $size, string $format = null)
 * @method static string distance(mixed $valueInMeters, string $format = 'km')
 * @method static string period(CarbonPeriod $value, string $format = null)
 * @method static string enum(mixed $enum, string $format = null)
 *
 * @see \Gsdk\Format\FormatFactory;
 * @see \App\Admin\Providers\FormatServiceProvider;
 * @see \App\Admin\Support\Format\ContactRule;
 * @see \App\Admin\Support\Format\ContractNumberRule;
 * @see \App\Admin\Support\Format\PriceRule;
 * @see \App\Admin\Support\Format\EnumRule;
 */
class Format extends Facade { }
