<?php

namespace App\Admin\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Admin\Support\View\Form\Form name(string $name)
 * @method static \App\Admin\Support\View\Form\Form method(string $method)
 * @method static \App\Admin\Support\View\Form\Form action(string $action)
 * @method static \App\Admin\Support\View\Form\Form select(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Form\Form hidden(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Form\Form number(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Form\Form text(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Form\Form email(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Form\Form phone(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Form\Form password(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Form\Form dateRange(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Form\Form currency(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Form\Form country(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Form\Form city(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Form\Form hotelType(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Form\Form checkbox(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Form\Form hotelRating(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Form\Form hotelStatus(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Form\Form enum(string $enumClass, string $name, array $options = [])
 * @method static \App\Admin\Support\View\Form\Form coordinates(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Form\Form client(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Form\Form hotel(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Form\Form localeText(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Form\Form language(string $name, array $options = [])
 *
 * @see \App\Admin\Support\View\Form\Form
 *
 */
class Form extends Facade
{
    protected static $cached = false;

    protected static function getFacadeAccessor()
    {
        return 'form';
    }
}
