<?php

namespace App\Admin\Support\Facades;

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
 *
 * @see \Gsdk\Format\FormatFactory;
 * @see \App\Admin\Providers\FormatServiceProvider;
 * @see \App\Admin\Support\Format\ContactRule;
 * @see \App\Admin\Support\Format\PriceRule;
 */
class Format extends Facade { }
