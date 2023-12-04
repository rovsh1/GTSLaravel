<?php

namespace App\Hotel\Support\Facades;

use Illuminate\Support\Facades\Facade;
use App\Hotel\Support\View\Form\FormBuilder;

/**
 * @method static FormBuilder name(string $name)
 * @method static FormBuilder failUrl(string $url)
 * @method static FormBuilder method(string $method)
 * @method static FormBuilder action(string $action)
 * @method static FormBuilder select(string $name, array $options = [])
 * @method static FormBuilder hidden(string $name, array $options = [])
 * @method static FormBuilder number(string $name, array $options = [])
 * @method static FormBuilder text(string $name, array $options = [])
 * @method static FormBuilder textarea(string $name, array $options = [])
 * @method static FormBuilder email(string $name, array $options = [])
 * @method static FormBuilder phone(string $name, array $options = [])
 * @method static FormBuilder password(string $name, array $options = [])
 * @method static FormBuilder file(string $name, array $options = [])
 * @method static FormBuilder dateRange(string $name, array $options = [])
 * @method static FormBuilder currency(string $name, array $options = [])
 * @method static FormBuilder country(string $name, array $options = [])
 * @method static FormBuilder city(string $name, array $options = [])
 * @method static FormBuilder hotelType(string $name, array $options = [])
 * @method static FormBuilder checkbox(string $name, array $options = [])
 * @method static FormBuilder hotelRating(string $name, array $options = [])
 * @method static FormBuilder hotelStatus(string $name, array $options = [])
 * @method static FormBuilder enum(string $name, array $options = [])
 * @method static FormBuilder coordinates(string $name, array $options = [])
 * @method static FormBuilder client(string $name, array $options = [])
 * @method static FormBuilder manager(string $name, array $options = [])
 * @method static FormBuilder hotel(string $name, array $options = [])
 * @method static FormBuilder localeText(string $name, array $options = [])
 * @method static FormBuilder language(string $name, array $options = [])
 * @method static FormBuilder numRange(string $name, array $options = [])
 * @method static void trySubmit(string $redirectUrl)
 *
 * @see FormBuilder
 *
 */
class Form extends Facade
{
    protected static $cached = false;

    protected static function getFacadeAccessor()
    {
        return FormBuilder::class;
    }
}
