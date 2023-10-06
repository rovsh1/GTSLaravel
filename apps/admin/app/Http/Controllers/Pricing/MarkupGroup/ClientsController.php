<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Pricing\MarkupGroup;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Client\Client;
use App\Admin\Models\Pricing\MarkupGroup;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Prototypes;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Admin\View\Menus\MarkupGroupMenu;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    private Prototype $prototype;

    public function __construct()
    {
        $this->prototype = Prototypes::get('markup-group');
    }

    public function index(Request $request, MarkupGroup $markupGroup): LayoutContract
    {
        $this->provider($markupGroup);

        $query = Client::where('markup_group_id', $markupGroup->id);
        $grid = $this->gridFactory()->data($query);

        return Layout::title('Клиенты')
            ->view('default.grid.grid', [
                'quicksearch' => $grid->getQuicksearch(),
                'grid' => $grid,
            ]);
    }

    protected function gridFactory(): GridContract
    {
        return Grid::paginator(16)
            ->text('name', [
                'text' => 'ФИО',
                'order' => true,
                'route' => fn($r) => route('client.show', $r)
            ]);
    }

    private function provider(MarkupGroup $markupGroup): void
    {
        Breadcrumb::prototype($this->prototype)
            ->add((string)$markupGroup)
            ->add('Клиенты');

        Sidebar::submenu(new MarkupGroupMenu($markupGroup, 'clients'));
    }
}
