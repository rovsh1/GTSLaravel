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
use App\Admin\Support\Facades\Client\LegalAdapter;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\Http\Actions\DefaultDestroyAction;
use App\Admin\Support\Http\Actions\DefaultFormCreateAction;
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

        return (new DefaultFormCreateAction($this->formFactory()))
            ->handle('Новое юр. лицо')
            ->view('default.form.form');
    }

    /**
     * @throws FormSubmitFailedException
     */
    public function store(Request $request, Client $client): RedirectResponse
    {
        $form = $this->formFactory()->method('post');

        $form->trySubmit(
            route('client.legals.create', ['client' => $client])
        );

        $formData = $form->getData();
        $legal = Legal::create($this->prepareLegalData($client->id, $formData));
        $this->setBankRequisites($legal, $formData);

        return redirect(route('client.legals.index', $client));
    }

    public function edit(Request $request, Client $client, Legal $legal)
    {
        $this->client($client);

        $legalData = LegalAdapter::find($legal->id);
        $form = $this->formFactory()
            ->action(route('client.legals.update', ['client' => $client, 'legal' => $legal]))
            ->method('put')
            ->data($legalData);

        return Layout::title((string)$legal)
            ->view('default.form.form', [
                'form' => $form,
                'cancelUrl' => route('client.legals.index', $client),
                'deleteUrl' => route('client.legals.destroy', ['client' => $client, 'legal' => $legal]),
            ]);
    }

    public function update(Client $client, Legal $legal): RedirectResponse
    {
        $form = $this->formFactory()->method('put');

        $failUrl = route('client.legals.edit', ['client' => $client, 'legal' => $legal]);
        $form->trySubmit($failUrl);

        $formData = $form->getData();
        $legal->update($this->prepareLegalData($client->id, $formData));
        $this->setBankRequisites($legal, $formData);

        return redirect(route('client.legals.index', $client));
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

    protected function formFactory(): FormContract
    {
        return Form::text('name', ['label' => 'Наименование', 'required' => true])
            ->select('industryId', ['label' => 'Индустрия', 'emptyItem' => '', 'items' => Legal\Industry::get()])
            ->enum('type', ['label' => 'Тип', 'emptyItem' => '', 'enum' => LegalTypeEnum::class, 'required' => true])
            ->text('address', ['label' => 'Адрес', 'required' => true])
            ->text('bik', ['label' => 'БИК'])
            ->text('cityName', ['label' => 'Город банка'])
            ->text('inn', ['label' => 'ИНН'])
            ->text('okpo', ['label' => 'Код ОКПО'])
            ->text('correspondentAccount', ['label' => 'Корреспондентский счет'])
            ->text('kpp', ['label' => 'КПП'])
            ->text('bankName', ['label' => 'Наименование банка'])
            ->text('currentAccount', ['label' => 'Рассчетный счет']);
    }

    private function prepareLegalData(int $clientId, array $data): array
    {
        return [
            ...$data,
            'industry_id' => $data['industryId'] ?? null,
            'client_id' => $clientId,
        ];
    }

    private function setBankRequisites(Legal $legal, array $data): void
    {
        LegalAdapter::setBankRequisites(
            clientLegalId: $legal->id,
            cityName: $data['cityName'] ?? '',
            currentAccount: $data['currentAccount'] ?? '',
            correspondentAccount: $data['correspondentAccount'] ?? '',
            kpp: $data['kpp'] ?? '',
            okpo: $data['okpo'] ?? '',
            inn: $data['inn'] ?? '',
            bik: $data['bik'] ?? '',
            bankName: $data['bankName'] ?? '',
        );
    }

    private function client(Client $hotel): void
    {
        Breadcrumb::prototype('client')
            ->addUrl(route('client.show', $hotel), (string)$hotel)
            ->addUrl(route('client.legals.index', $hotel), 'Юр. лица');

        Sidebar::submenu(new ClientMenu($hotel, 'legals'));
    }
}
