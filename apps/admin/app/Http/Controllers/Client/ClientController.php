<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Client;

use App\Admin\Http\Requests\Client\CreateClientRequest;
use App\Admin\Http\Resources\Client as ClientResource;
use App\Admin\Models\Client\Client;
use App\Admin\Models\Client\Legal;
use App\Admin\Models\Client\User;
use App\Admin\Models\Pricing\MarkupGroup;
use App\Admin\Models\Reference\Country;
use App\Admin\Repositories\ClientAdministratorRepository;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\Client\LegalAdapter;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Grid\SearchForm;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Admin\View\Menus\ClientMenu;
use Gsdk\Format\View\ParamsTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Sdk\Shared\Enum\Client\LanguageEnum;
use Sdk\Shared\Enum\Client\ResidencyEnum;
use Sdk\Shared\Enum\Client\StatusEnum;
use Sdk\Shared\Enum\Client\TypeEnum;
use Sdk\Shared\Enum\Client\User\RoleEnum;
use Sdk\Shared\Enum\Client\User\StatusEnum as UserStatusEnum;

class ClientController extends AbstractPrototypeController
{
    public function __construct(
        private readonly ClientAdministratorRepository $clientAdministratorRepository
    ) {
        parent::__construct();
    }

    protected function getPrototypeKey(): string
    {
        return 'client';
    }

    public function store(): RedirectResponse
    {
        $form = $this->formFactory()
            ->method('post');

        $form->submitOrFail($this->prototype->route('create'));

        $preparedData = $this->saving($form->getData());
        $this->model = $this->repository->create($preparedData);
        if ($this->model->type === TypeEnum::PHYSICAL) {
            $gender = $preparedData['gender'] ?? null;
            $this->createUser($preparedData['country_id'], $gender);
        }

        $managerId = $preparedData['administrator_id'] ?? null;
        if ($managerId === null) {
            $managerId = request()->user()->id;
        }
        $this->clientAdministratorRepository->create($this->model->id, $managerId);

        $redirectUrl = $this->prototype->route('index');
        if ($this->hasShowAction()) {
            $redirectUrl = $this->prototype->route('show', $this->model);
        }

        return redirect($redirectUrl);
    }

    public function storeDialog(CreateClientRequest $request): JsonResponse
    {
        $data = $this->saving([
            'city_id' => $request->getCityId(),
            'currency' => $request->getCurrency(),
            'type' => $request->getType(),
            'name' => $request->getName(),
            'status' => $request->getStatus() ?? StatusEnum::ACTIVE,
            'residency' => $request->getResidency(),
            'language' => $request->getLanguage(),
            'markup_group_id' => $request->getMarkupGroupId(),
        ]);
        $this->model = $this->repository->create($data);
        $legalData = $request->getLegal();
        if ($legalData !== null) {
            $legal = Legal::create([
                'client_id' => $this->model->id,
                'city_id' => $legalData->cityId,
                'industry_id' => $legalData->industry,
                'name' => $legalData->name,
                'address' => $legalData->address,
            ]);

            LegalAdapter::setBankRequisites(
                clientLegalId: $legal->id,
                bik: $legalData->bik ?? '',
                inn: $legalData->inn ?? '',
                okpo: $legalData->okpoCode ?? '',
                correspondentAccount: $legalData->corrAccount ?? '',
                kpp: $legalData->kpp ?? '',
                bankName: $legalData->bankName ?? '',
                currentAccount: $legalData->currentAccount ?? '',
                cityName: $legalData->bankCity ?? ''
            );
        }

        $physical = $request->getPhysical();
        if ($this->model->type === TypeEnum::PHYSICAL && $physical !== null) {
            $this->createUser($physical->countryId, $physical->gender);
        }

        $managerId = $request->user()->id;
        if ($request->getManagerId() !== null) {
            $managerId = $request->getManagerId();
        }
        $this->clientAdministratorRepository->create($this->model->id, $managerId);

        return response()->json(
            ClientResource::make($this->model)
        );
    }

    public function edit(int $id): LayoutContract
    {
        $layout = parent::edit($id);
        Sidebar::submenu(new ClientMenu($this->model, 'info'));

        return $layout;
    }

    public function update(int $id): RedirectResponse
    {
        $this->model = $this->repository->findOrFail($id);

        $form = $this->form()
            ->method('put')
            ->failUrl($this->prototype->route('edit', $this->model));

        $form->submitOrFail();

        $preparedData = $this->saving($form->getData());
        $newAdministratorId = (int)$preparedData['administrator_id'];
        if (!empty($newAdministratorId) && $this->model->administrator_id !== $newAdministratorId) {
            $this->clientAdministratorRepository->update($id, $newAdministratorId);
        }
        $this->repository->update($this->model->id, $preparedData);

        $redirectUrl = $this->prototype->route('show', $this->model);

        return redirect($redirectUrl);
    }

    public function currencies(Client $client): JsonResponse
    {
        $currency = $client->currency;

        return response()->json([
            ['id' => $currency->value, 'name' => $currency->name]
        ]);
    }

    public function list(): JsonResponse
    {
        return response()->json(
            ClientResource::collection(
                $this->repository->query()->orderBy('name')->get()
            )
        );
    }

    protected function saving(array $data): array
    {
        return array_merge($data, ['is_b2b' => true]);
    }

    protected function getShowViewData(): array
    {
        $showUrl = $this->prototype->route('show', $this->model->id);
        $isUpdateAllowed = Acl::isUpdateAllowed($this->getPrototypeKey());

        return [
            'params' => $this->clientParams(),
            'contactsUrl' => $showUrl . '/contacts',
            'contactsEditable' => $isUpdateAllowed,
            'contacts' => $this->model->contacts,
        ];
    }

    protected function prepareShowMenu(Model $model)
    {
        Sidebar::submenu(new ClientMenu($model, 'info'));
    }

    private function clientParams(): ParamsTable
    {
        return (new ParamsTable())
            ->id('id', 'ID')
            ->text('name', 'Наименование')
            ->enum('type', 'Тип', TypeEnum::class)
            ->text('city_name', 'Город')
            ->text('currency_name', 'Валюта')
            ->text('markup_group_name', 'Группа наценки')
            ->enum('residency', 'Тип цены', ResidencyEnum::class)
            ->enum('language', 'Язык', LanguageEnum::class)
            ->text('administrator_name', 'Менеджер')
            ->data($this->model);
    }

    private function createUser(int $countryId, int|string|null $gender): void
    {
        User::create([
            'client_id' => $this->model->id,
            'country_id' => $countryId,
            'gender' => $gender !== null ? (int)$gender : null,
            'name' => $this->model->name,
            'presentation' => $this->model->name,
            'role' => RoleEnum::CUSTOMER,
            'status' => UserStatusEnum::ACTIVE,
        ]);
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
            ->setSearchForm($this->searchForm())
            ->paginator(self::GRID_LIMIT)
            ->text('name', ['text' => 'ФИО', 'route' => $this->prototype->routeName('show')])
            ->enum('type', ['text' => 'Тип', 'enum' => TypeEnum::class])
            ->text('markup_group_name', ['text' => 'Наценка'])
            ->text('country_name', ['text' => 'Страна'])
            ->text('city_name', ['text' => 'Город'])
            ->enum('status', ['text' => 'Статус', 'enum' => StatusEnum::class])
            ->orderBy('name', 'asc');
    }

    protected function formFactory(): FormContract
    {
        return Form::text('name', ['label' => 'ФИО или название компании', 'required' => true])
            ->enum('type', ['label' => 'Тип', 'enum' => TypeEnum::class, 'required' => true, 'emptyItem' => ''])
            ->hidden('gender', ['label' => 'Пол'])
            ->country('country_id', ['label' => 'Страна (гражданство)', 'emptyItem' => ''])
            ->city('city_id', ['label' => 'Город', 'emptyItem' => ''])
            ->enum('status', ['label' => 'Статус', 'enum' => StatusEnum::class])
            ->currency('currency', ['label' => 'Валюта', 'required' => true, 'emptyItem' => ''])
            ->enum(
                'residency',
                ['label' => 'Группа наценки', 'enum' => ResidencyEnum::class, 'required' => true, 'emptyItem' => '']
            )
            ->enum(
                'language',
                ['label' => 'Язык', 'enum' => LanguageEnum::class, 'required' => true, 'emptyItem' => '']
            )
            ->select('markup_group_id', [
                'label' => 'Группа наценки',
                'required' => true,
                'emptyItem' => '',
                'items' => MarkupGroup::get()
            ])
            ->manager('administrator_id', ['label' => 'Менеджер', 'emptyItem' => '']);
    }

    private function searchForm()
    {
        return (new SearchForm())
            ->select('country_id', ['label' => 'Страна', 'emptyItem' => '', 'items' => Country::get()])
            ->city('city_id', ['label' => 'Город', 'emptyItem' => ''])
            ->enum('type', ['label' => 'Тип', 'enum' => TypeEnum::class, 'emptyItem' => ''])
            ->enum('status', ['label' => 'Источник', 'enum' => StatusEnum::class, 'emptyItem' => ''])
            ->select('markup_group_id', [
                'label' => 'Группа наценки',
                'emptyItem' => '',
                'items' => MarkupGroup::get()
            ]);
    }
}
