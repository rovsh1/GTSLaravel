<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Supplier\Service\Price;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Supplier\Supplier;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Prototypes;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\View\Menus\SupplierMenu;

class AbstractPricesController extends Controller
{
    protected Prototype $prototype;

    public function __construct()
    {
        $this->prototype = Prototypes::get('supplier');
    }

    protected function provider(Supplier $provider): void
    {
        Breadcrumb::prototype($this->prototype)
            ->addUrl(
                $this->prototype->route('show', $provider),
                (string)$provider
            );
        Breadcrumb::add('Цены');

        Sidebar::submenu(new SupplierMenu($provider, 'prices'));
    }

}
