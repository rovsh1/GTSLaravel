<?php

namespace App\Admin\Http\Controllers\Client;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Enums\Contract\StatusEnum;
use App\Admin\Exceptions\FormSubmitFailedException;
use App\Admin\Http\Actions\Hotel\UpdateContractAction;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Client\Client;
use App\Admin\Models\Client\Document;
use App\Admin\Models\Client\DocumentTypeEnum;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Format;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Prototypes;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\Http\Actions\DefaultDestroyAction;
use App\Admin\Support\Http\Actions\DefaultFormCreateAction;
use App\Admin\Support\Http\Actions\DefaultFormEditAction;
use App\Admin\Support\Http\Actions\DefaultFormStoreAction;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Admin\View\Menus\ClientMenu;
use App\Shared\Http\Responses\AjaxResponseInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    private Prototype $prototype;

    public function __construct()
    {
        $this->prototype = Prototypes::get('client');
    }

    public function index(Request $request, Client $client): LayoutContract
    {
        $this->client($client);

        $query = Document::whereClientId($client->id);
        $grid = $this->gridFactory($client->id)->data($query);

        return Layout::title('Документы')
            ->view('default.grid.grid', [
                'grid' => $grid,
                'editAllowed' => $this->isUpdateAllowed(),
                'deleteAllowed' => $this->isUpdateAllowed(),
                'createUrl' => $this->isUpdateAllowed() ? $this->prototype->route('documents.create', $client) : null,
            ]);
    }

    public function create(Request $request, Client $client): LayoutContract
    {
        $this->client($client);

        return (new DefaultFormCreateAction($this->formFactory($client->id)))
            ->handle('Новый документ');
    }

    /**
     * @throws FormSubmitFailedException
     */
    public function store(Request $request, Client $client): RedirectResponse
    {
        return (new DefaultFormStoreAction($this->formFactory($client->id)))
            ->handle(Document::class);
    }

    public function edit(Request $request, Client $client, Document $document)
    {
        $this->client($client);

        return (new DefaultFormEditAction($this->formFactory($client->id)))
            ->deletable()
            ->handle($document);
    }

    public function update(Client $client, Document $document): RedirectResponse
    {
        return (new UpdateContractAction($this->formFactory($client->id)))
            ->handle($document);
    }

    public function destroy(Client $client, Document $document): AjaxResponseInterface
    {
        return (new DefaultDestroyAction())->handle($document);
    }

    public function get(Client $client, Document $document): JsonResponse
    {
        return response()->json($document);
    }

    protected function formFactory(int $clientId): FormContract
    {
        return Form::name('data')
            ->setOption('enctype', 'multipart/form-data')
            ->hidden('client_id', ['value' => $clientId])
            ->enum('type', ['label' => 'Тип документа', 'enum' => DocumentTypeEnum::class, 'required' => true])
            ->enum('status', ['label' => 'Статус', 'emptyItem' => '', 'enum' => StatusEnum::class, 'required' => true])
            ->text('number', ['label' => 'Номер документа', 'required' => true])
            ->dateRange('period', ['label' => 'Период', 'required' => true])
            ->file('files', ['label' => 'Документы', 'multiple' => true]);
    }

    protected function gridFactory(int $clientId): GridContract
    {
        return Grid::paginator(16)
            ->edit(fn(Document $document) => route('client.documents.edit', [$clientId, $document]))
            ->enum('type', ['text' => 'Тип документа', 'enum' => DocumentTypeEnum::class])
            ->text('number', ['text' => 'Номер документа', 'order' => true])
            ->text('period', ['text' => 'Период', 'renderer' => fn($r, $t) => Format::period($t)])
            ->enum('status', ['text' => 'Статус', 'enum' => StatusEnum::class, 'order' => true])
            ->file('files', ['text' => 'Документы']);
    }

    private function client(Client $client): void
    {
        Breadcrumb::prototype('client')
            ->addUrl(route('client.show', $client), (string)$client)
            ->addUrl(route('client.documents.index', $client), 'Документы');

        Sidebar::submenu(new ClientMenu($client, 'documents'));
    }

    private function isUpdateAllowed(): bool
    {
        return Acl::isUpdateAllowed('client');
    }
}
