<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Exceptions\FormSubmitFailedException;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Models\Hotel\Rule;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\Http\Actions\DefaultDestroyAction;
use App\Admin\Support\Http\Actions\DefaultFormCreateAction;
use App\Admin\Support\Http\Actions\DefaultFormEditAction;
use App\Admin\Support\Http\Actions\DefaultFormUpdateAction;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Admin\View\Menus\HotelMenu;
use App\Shared\Http\Responses\AjaxReloadResponse;
use App\Shared\Http\Responses\AjaxResponseInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RuleController extends Controller
{
    public function create(Request $request, Hotel $hotel): LayoutContract
    {
        $this->hotel($hotel);

        return (new DefaultFormCreateAction($this->formFactory($hotel->id)))
            ->handle('Новое правило')
            ->view('default.dialog-form');
    }

    /**
     * @throws FormSubmitFailedException
     */
    public function store(Request $request, Hotel $hotel): AjaxResponseInterface
    {
        $form = $this->formFactory($hotel->id)
            ->method('post');

        $form->submitOrFail(route('hotels.rules.create', $hotel));

        Rule::create($form->getData());

        return new AjaxReloadResponse();
    }

    public function edit(Request $request, Hotel $hotel, Rule $rule)
    {
        $this->hotel($hotel);

        return (new DefaultFormEditAction($this->formFactory($hotel->id)))
            ->deletable()
            ->cancelUrl(route('hotels.settings.index', $hotel))
            ->handle($rule);
    }

    public function update(Hotel $hotel, Rule $rule): RedirectResponse
    {
        return (new DefaultFormUpdateAction($this->formFactory($hotel->id)))
            ->successUrl(route('hotels.settings.index', $hotel))
            ->handle($rule);
    }

    public function destroy(Hotel $hotel, Rule $rule): AjaxResponseInterface
    {
        return (new DefaultDestroyAction())
            ->redirectUrl(route('hotels.settings.index', $hotel))
            ->handle($rule);
    }

    protected function formFactory(int $hotelId): FormContract
    {
        return Form::name('data')
            ->hidden('hotel_id', ['value' => $hotelId])
            ->localeText('name', ['label' => 'Название', 'required' => true])
            ->localeTextarea('text', ['label' => 'Текст', 'required' => true]);
    }

    private function hotel(Hotel $hotel): void
    {
        Breadcrumb::prototype('hotel')
            ->addUrl(route('hotels.show', $hotel), (string)$hotel)
            ->addUrl(route('hotels.settings.index', $hotel), 'Условия размещения');

        Sidebar::submenu(new HotelMenu($hotel, 'settings'));
    }
}
