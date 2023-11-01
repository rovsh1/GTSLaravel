<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Client;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Client\Client;
use App\Admin\Models\Client\User;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Prototypes;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Admin\View\Menus\ClientMenu;
use App\Shared\Http\Responses\AjaxErrorResponse;
use App\Shared\Http\Responses\AjaxReloadResponse;
use App\Shared\Http\Responses\AjaxResponseInterface;
use App\Shared\Http\Responses\AjaxSuccessResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ClientUserController extends Controller
{

    private Prototype $prototype;

    public function __construct()
    {
        $this->prototype = Prototypes::get($this->getPrototypeKey());
    }

    protected function getPrototypeKey(): string
    {
        return 'client-user';
    }

    public function index(Request $request, Client $client): LayoutContract
    {
        $this->client($client);

        $grid = $this->gridFactory();
        $grid->data($client->users);

        return Layout::title('Пользователи')
            ->view('client.user.main.main', [
                'grid' => $grid,
                'createUrl' => Acl::isCreateAllowed('client-user') ? route('client.users.create', $client) : null,
                'createUserUrl' => Acl::isCreateAllowed('client-user') ? $this->prototype->route('create') : null,
            ]);
    }

    public function create(Request $request, Client $client): View
    {
        $form = $this->formFactory()
            ->method('post')
            ->action(route('client.users.store', $client));

        return view('default.dialog-form', [
            'form' => $form
        ]);
    }

    public function store(Client $client): AjaxResponseInterface
    {
        $form = $this->formFactory()
            ->method('post');

        if (!$form->submit()) {
            return new AjaxErrorResponse('');
        }

        $userId = $form->getData()['user_id'] ?? null;
        if ($userId === null) {
            return new AjaxErrorResponse('Необходимо выбрать пользователя');
        }
        User::find($userId)->update(['client_id' => $client->id]);

        return new AjaxReloadResponse();
    }

    public function bulkDelete(Request $request, Client $client): AjaxResponseInterface
    {
        $userIds = $request->get('ids');
        if ($userIds === null || count($userIds) === 0) {
            return new AjaxSuccessResponse();
        }
        User::whereIn('users.id', $userIds)->update(['client_id' => null]);

        return new AjaxSuccessResponse();
    }

    protected function gridFactory(): GridContract
    {
        return Grid::paginator(16)
            ->checkbox('checked', ['checkboxClass' => 'js-select-user', 'dataAttributeName' => 'user-id'])
            ->text(
                'name',
                [
                    'text' => 'ФИО',
                    'route' => $this->prototype->routeName('show'),
                    'renderer' => fn($user) => (string)$user,
                    'order' => true
                ]
            )
            ->text('city_name', ['text' => 'Город'])
            ->text('email', ['text' => 'Email'])
            ->text('phone', ['text' => 'Телефон']);
    }

    protected function formFactory(): FormContract
    {
        $users = User::whereNull('users.client_id')->get();
        return Form::select(
            'user_id',
            ['label' => 'Пользователь', 'emptyItem' => '', 'items' => $users]
        );
    }

    private function client(Client $hotel): void
    {
        Breadcrumb::prototype('client')
            ->addUrl(route('client.show', $hotel), (string)$hotel)
            ->addUrl(route('client.users.index', $hotel), 'Пользователи');

        Sidebar::submenu(new ClientMenu($hotel, 'users'));
    }
}
