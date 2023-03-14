<?php

namespace App\Admin\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Admin\Support\View\Form\Form method(string $method)
 * @method static \App\Admin\Support\View\Form\Form action(string $action)
 * @method static \App\Admin\Support\View\Form\Form select(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Form\Form hidden(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Form\Form text(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Form\Form country(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Form\Form city(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Form\Form hotelType(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Form\Form clientType(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Form\Form hotelRating(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Form\Form hotelStatus(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Form\Form coordinates(string $name, array $options = [])
 *
 * @see \App\Admin\Support\View\Form\Form
 *
 */
class Form extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'form';
    }
}
