<?php

namespace App\Hotel\Support\Facades;

use App\Hotel\Support\View\Form\FormBuilder;
use App\Hotel\Support\View\Grid\GridBuilder;
use Gsdk\Grid\Paginator;
use Illuminate\Support\Facades\Facade;

/**
 * @method static GridBuilder edit(string|array|callable $options)
 * @method static GridBuilder enableQuicksearch()
 * @method static GridBuilder setSearchForm(FormBuilder $form)
 * @method static GridBuilder data(mixed $data)
 * @method static GridBuilder orderBy(string $name, string $order = 'asc')
 * @method static GridBuilder paginator(int|Paginator $paginator = null)
 * @method static GridBuilder text(string $name, array $options = [])
 * @method static GridBuilder email(string $name, array $options = [])
 * @method static GridBuilder phone(string $name, array $options = [])
 * @method static GridBuilder number(string $name, array $options = [])
 * @method static GridBuilder date(string $name, array $options = [])
 * @method static GridBuilder url(string $name, array $options = [])
 * @method static GridBuilder file(string $name, array $options = [])
 * @method static GridBuilder language(string $name, array $options = [])
 *
 * @see GridBuilder
 *
 */
class Grid extends Facade
{
    protected static $cached = false;

    protected static function getFacadeAccessor()
    {
        return GridBuilder::class;
    }
}
