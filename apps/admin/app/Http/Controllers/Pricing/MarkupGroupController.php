<?php

namespace App\Admin\Http\Controllers\Pricing;

use App\Admin\Http\Resources\Pricing\MarkupGroup as MarkupGroupResource;
use App\Admin\Models\Pricing\MarkupGroup;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\ActionsMenu;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\View\Menus\MarkupGroupMenu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Module\Shared\Enum\Pricing\MarkupValueTypeEnum;

class MarkupGroupController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'markup-group';
    }

    public function list(): JsonResponse
    {
        $groups = MarkupGroup::get();

        return response()->json(
            MarkupGroupResource::collection($groups)
        );
    }

    protected function formFactory(): FormContract
    {
        return Form::text('name', ['label' => 'Наименование', 'required' => true])
            ->enum(
                'type',
                ['label' => 'Тип значения', 'emptyItem' => '', 'enum' => MarkupValueTypeEnum::class, 'required' => true]
            )
            ->number('value', ['label' => 'Значение', 'required' => true]);
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
            ->paginator(self::GRID_LIMIT)
            ->edit($this->prototype)
            ->text('name', [
                'text' => 'Наименование',
                'route' => $this->prototype->routeName('rules.index'),
                'order' => true
            ])
            ->text('value', [
                'text' => 'Наценка',
                'renderer' => fn($r, $v) => $r->type === MarkupValueTypeEnum::PERCENT ? "{$v}%" : $v
            ]);
    }

    protected function prepareShowMenu(Model $model)
    {
        $menu = ActionsMenu::getFacadeRoot();
        if (Acl::isUpdateAllowed($this->getPrototypeKey())) {
            $menu->addUrl($this->prototype->route('edit', $model), [
                'icon' => 'edit',
                'cls' => 'btn-edit',
                'text' => 'Редактировать'
            ]);
        }

        if (Acl::isUpdateAllowed($this->getPrototypeKey())) {
            $menu->addUrl($this->prototype->route('destroy', $model), [
                'icon' => 'delete',
                'cls' => 'btn-delete',
                'text' => 'Удалить'
            ]);
        }

        Sidebar::submenu(new MarkupGroupMenu($model, 'info'));
    }

    protected function prepareEditMenu(Model $model)
    {
        Sidebar::submenu(new MarkupGroupMenu($model, 'info'));
    }

    protected function getShowViewData(): array
    {
        return [
//            'contactsUrl' => $this->prototype->route('show', $this->model->id) . '/contacts',
//            'contactsEditable' => Acl::isUpdateAllowed($this->getPrototypeKey()),
//            'contacts' => $this->model->contacts
        ];
    }
}
