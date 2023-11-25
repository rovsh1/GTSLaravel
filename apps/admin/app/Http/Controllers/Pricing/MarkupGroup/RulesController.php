<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Pricing\MarkupGroup;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Pricing\MarkupGroup;
use App\Admin\Models\Pricing\MarkupGroupRule;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Prototypes;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Admin\View\Menus\MarkupGroupMenu;
use App\Shared\Http\Responses\AjaxRedirectResponse;
use App\Shared\Http\Responses\AjaxResponseInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Module\Shared\Enum\Pricing\MarkupValueTypeEnum;

class RulesController extends Controller
{
    private Prototype $prototype;

    public function __construct()
    {
        $this->prototype = Prototypes::get('markup-group');
    }

    public function index(Request $request, MarkupGroup $markupGroup): LayoutContract
    {
        $this->provider($markupGroup);

        $query = MarkupGroupRule::withDetails()->where('group_id', $markupGroup->id);
        $grid = $this->gridFactory()->data($query);

        return Layout::title('Наценки отелей')
            ->view('default.grid.grid', [
                'quicksearch' => $grid->getQuicksearch(),
                'grid' => $grid,
                'createUrl' => $this->prototype->route('rules.create', $markupGroup)
            ]);
    }

    public function create(MarkupGroup $markupGroup): LayoutContract
    {
        $this->provider($markupGroup);

        $form = $this->formFactory($markupGroup)
            ->method('post')
            ->action($this->prototype->route('rules.store', $markupGroup));

        return Layout::title($this->prototype->title('create'))
            ->view($this->formPath(), [
                'form' => $form,
                'cancelUrl' => $this->prototype->route('rules.index', $markupGroup)
            ]);
    }

    public function store(MarkupGroup $markupGroup): RedirectResponse
    {
        $fallbackUrl = $this->prototype->route('rules.create', $markupGroup);
        $form = $this->formFactory($markupGroup)
            ->method('post')
            ->failUrl($fallbackUrl);

        $form->submitOrFail();

        $data = $form->getData();
        $hotelId = $data['hotel_id'] ?? null;
        $roomId = $data['room_id'] ?? null;
        $existRuleQuery = MarkupGroupRule::whereHotelId($hotelId);
        if ($roomId !== null) {
            $existRuleQuery->whereRoomId($roomId);
        }
        if ($existRuleQuery->exists()) {
            $form->throwException(new \RuntimeException('Наценка уже существует. Обновите её вместо создания новой.'));
        }

        $this->model = MarkupGroupRule::create($form->getData());
        $redirectUrl = $this->prototype->route('rules.index', $markupGroup);

        return redirect($redirectUrl);
    }

    public function edit(MarkupGroup $markupGroup, int $id): LayoutContract
    {
        $this->provider($markupGroup);

        $model = MarkupGroupRule::findOrFail($id);

        $form = $this->formFactory($markupGroup)
            ->method('put')
            ->action($this->prototype->route('rules.update', [$markupGroup, $model->id]))
            ->data($model);

        return Layout::title('Редактирование')
            ->view($this->formPath(), [
                'model' => $model,
                'form' => $form,
                'cancelUrl' => $this->prototype->route('rules.index', $markupGroup),
                'deleteUrl' => $this->prototype->route('rules.destroy', [$markupGroup, $model->id])
            ]);
    }

    public function update(MarkupGroup $markupGroup, int $id): RedirectResponse
    {
        $this->model = MarkupGroupRule::findOrFail($id);

        $form = $this->formFactory($markupGroup)
            ->method('put');

        $form->submitOrFail($this->prototype->route('rules.edit', [$markupGroup, $this->model]));

        MarkupGroupRule::whereId($id)->update($form->getData());

        $redirectUrl = $this->prototype->route('rules.index', $markupGroup);

        return redirect($redirectUrl);
    }

    public function destroy(MarkupGroup $markupGroup, int $id): AjaxResponseInterface
    {
        MarkupGroupRule::whereId($id)->delete();

        return new AjaxRedirectResponse($this->prototype->route('rules.index', $markupGroup));
    }

    protected function formFactory(MarkupGroup $markupGroup): FormContract
    {
        return Form::hidden('group_id', ['value' => $markupGroup->id])
            ->hotel('hotel_id', ['label' => 'Отель', 'emptyItem' => '', 'required' => true])
            ->hidden('room_id', ['label' => 'Номер'])
            ->enum(
                'type',
                ['label' => 'Тип значения', 'emptyItem' => '', 'enum' => MarkupValueTypeEnum::class, 'required' => true]
            )
            ->number('value', ['label' => 'Значение', 'required' => true]);
    }

    protected function gridFactory(): GridContract
    {
        return Grid::paginator(16)
            ->edit(fn($r) => $this->prototype->route('rules.edit', [$r->group_id, $r]))
            ->text('hotel_name', ['text' => 'Отель'])
            ->text('hotel_room_name', ['text' => 'Номер'])
            ->text('value', [
                'text' => 'Наценка',
                'renderer' => fn($r, $v) => $r->type === MarkupValueTypeEnum::PERCENT ? "{$v}%" : $v
            ]);
    }

    private function provider(MarkupGroup $markupGroup): void
    {
        Breadcrumb::prototype($this->prototype)
            ->addUrl(
                $this->prototype->route('rules.index', $markupGroup),
                (string)$markupGroup
            )
            ->add('Наценки отелей');

        Sidebar::submenu(new MarkupGroupMenu($markupGroup, 'rules'));
    }

    private function formPath(): string
    {
        return 'markup-group.rules.form.form';
    }
}
