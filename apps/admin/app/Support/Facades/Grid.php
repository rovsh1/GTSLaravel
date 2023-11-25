<?php

namespace App\Admin\Support\Facades;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Support\View\Form\Form;
use Gsdk\Grid\Paginator;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Admin\Support\View\Grid\Grid edit(Prototype|string|array|callable $options)
 * @method static \App\Admin\Support\View\Grid\Grid enableQuicksearch()
 * @method static \App\Admin\Support\View\Grid\Grid setSearchForm(Form $form)
 * @method static \App\Admin\Support\View\Grid\Grid data(mixed $data)
 * @method static \App\Admin\Support\View\Grid\Grid orderBy(string $name, string $order = 'asc')
 * @method static \App\Admin\Support\View\Grid\Grid paginator(int|Paginator $paginator = null)
 * @method static \App\Admin\Support\View\Grid\Grid text(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Grid\Grid email(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Grid\Grid phone(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Grid\Grid number(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Grid\Grid date(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Grid\Grid url(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Grid\Grid file(string $name, array $options = [])
 * @method static \App\Admin\Support\View\Grid\Grid language(string $name, array $options = [])
 *
 * @see \App\Admin\Support\View\Grid\Grid
 *
 */
class Grid extends Facade
{
    protected static $cached = false;

    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\View\Grid\Grid::class;
    }
}
