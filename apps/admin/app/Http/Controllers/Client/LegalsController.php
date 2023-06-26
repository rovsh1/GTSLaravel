<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Client;

use App\Admin\Exceptions\FormSubmitFailedException;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Http\Requests\Client\SearchLegalsRequest;
use App\Admin\Http\Resources\Client\Legal as LegalResource;
use App\Admin\Models\Client\Client;
use App\Admin\Models\Client\Legal;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\Http\Actions\DefaultDestroyAction;
use App\Admin\Support\Http\Actions\DefaultFormCreateAction;
use App\Admin\Support\Http\Actions\DefaultFormEditAction;
use App\Admin\Support\Http\Actions\DefaultFormStoreAction;
use App\Admin\Support\Http\Actions\DefaultFormUpdateAction;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Admin\View\Menus\ClientMenu;
use App\Core\Support\Http\Responses\AjaxResponseInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Module\Shared\Enum\Client\LegalTypeEnum;

class LegalsController extends Controller
{
    public function index(Request $request, Client $client): LayoutContract
    {
        $this->client($client);

        $grid = $this->gridFactory();
        $grid->data($client->legals);

        return Layout::title('Юр. лица')
            ->view('default.grid.grid', [
                'grid' => $grid,
                'editAllowed' => true,
                'deleteAllowed' => true,
                'createUrl' => Acl::isCreateAllowed('client') ? route('client.legals.create', $client) : null,
            ]);
    }

    public function create(Request $request, Client $client): LayoutContract
    {
        $this->client($client);

        return (new DefaultFormCreateAction($this->formFactory($client)))
            ->handle('Новое юр. лицо')
            ->view('default.form.form');
    }

    /**
     * @throws FormSubmitFailedException
     */
    public function store(Request $request, Client $client): RedirectResponse
    {
        return (new DefaultFormStoreAction($this->formFactory($client)))
            ->handle(Legal::class);
    }

    public function edit(Request $request, Client $client, Legal $legal)
    {
        $this->client($client);

        return (new DefaultFormEditAction($this->formFactory($client)))
            ->deletable()
            ->handle($legal)
            ->view('default.form.form');
    }

    public function update(Client $client, Legal $legal): RedirectResponse
    {
        return (new DefaultFormUpdateAction($this->formFactory($client)))
            ->handle($legal);
    }

    public function destroy(Client $client, Legal $legal): AjaxResponseInterface
    {
        return (new DefaultDestroyAction())->handle($legal);
    }

    public function search(SearchLegalsRequest $request): JsonResponse
    {
        $legals = Legal::whereClientId($request->getClientId())->get();

        return response()->json(
            LegalResource::collection($legals)
        );
    }

    protected function gridFactory(): GridContract
    {
        return Grid::edit(fn(Legal $legal) => route('client.legals.edit', [$legal->client_id, $legal]))
            ->text('name', ['text' => 'Наименование'])
            ->text('industry_name', ['text' => 'Индустрия'])
            ->enum('type', ['text' => 'Тип', 'enum' => LegalTypeEnum::class])
            ->text('address', ['text' => 'Адрес']);
    }

    protected function formFactory(Client $client): FormContract
    {
        return Form::text('name', ['label' => 'Наименование'])
            ->hidden('client_id', ['value' => $client->id])
            ->select('industry_id', ['label' => 'Индустрия', 'emptyItem' => '', 'items' => Legal\Industry::get()])
            ->enum('type', ['label' => 'Тип', 'emptyItem' => '', 'enum' => LegalTypeEnum::class, 'required' => true])
            ->text('address', ['label' => 'Адрес', 'required' => true])
            //@todo сейчас реквизиты не сохраняются в json
            ->text('bik', ['label' => 'БИК'])
            ->city('city_id', ['label' => 'Город банка', 'emptyItem' => ''])
            ->text('inn', ['label' => 'ИНН'])
            ->text('okpo', ['label' => 'Код ОКПО'])
            ->text('correspondent_account', ['label' => 'Корреспондентский счет'])
            ->text('kpp', ['label' => 'КПП'])
            ->text('bank_name', ['label' => 'Наименование банка'])
            ->text('current_account', ['label' => 'Рассчетный счет']);
    }

    private function client(Client $hotel): void
    {
        Breadcrumb::prototype('client')
            ->addUrl(route('client.show', $hotel), (string)$hotel)
            ->addUrl(route('client.legals.index', $hotel), 'Юр. лица');

        Sidebar::submenu(new ClientMenu($hotel, 'legals'));
    }
}
