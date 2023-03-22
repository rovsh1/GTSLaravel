<?php

namespace App\Admin\Support\Http\Controllers;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Enums\ContactTypeEnum;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Prototypes;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Core\Support\Http\Responses\AjaxErrorResponse;
use App\Core\Support\Http\Responses\AjaxReloadResponse;
use Illuminate\Contracts\View\View;

abstract class AbstractContactController extends Controller
{
    protected Prototype $prototype;

    public function __construct()
    {
        $this->prototype = Prototypes::get($this->getPrototypeKey());
    }

    public function callAction($method, $parameters)
    {
        if (!Acl::isAllowed($this->getPrototypeKey(), 'update')) {
            abort(403);
        }

        return parent::callAction($method, $parameters);
    }

    public function create(int $providerId): View
    {
        return view('default.dialog-form', [
            'form' => $this->formFactory()
                ->action($this->route('store', $providerId))
        ]);
    }

    public function store(int $providerId): AjaxErrorResponse|AjaxReloadResponse
    {
        $form = $this->formFactory()
            ->method('post');

        if (!$form->submit()) {
            return new AjaxErrorResponse('');
//            return redirect($this->prototype->route('create'))
//                ->withErrors($form->errors())
//                ->withInput();
        }

        $data = $form->getData();
        $data['provider_id'] = $providerId;
        $contact = ($this->getContactModel())::create($data);

        return new AjaxReloadResponse();
    }

    public function edit(int $providerId, int $id): View
    {
        $contact = $this->findContact($id);

        return view('default.dialog-form', [
            'form' => $this->formFactory()
                ->method('put')
                ->action($this->route('update', [$providerId, $id]))
                ->data($contact)
        ]);
    }

    public function update(int $parentId, int $id): AjaxErrorResponse|AjaxReloadResponse
    {
        $contact = $this->findContact($id);

        $form = $this->formFactory()
            ->method('put');

        if (!$form->submit()) {
            return new AjaxErrorResponse('');
//            return redirect($this->prototype->route('create'))
//                ->withErrors($form->errors())
//                ->withInput();
        }

        $contact->update($form->getData());

        return new AjaxReloadResponse();
    }

    public function destroy(int $parentId, int $id): AjaxReloadResponse
    {
        $this->findContact($id)->delete();

        return new AjaxReloadResponse();
    }

    protected function formFactory(): FormContract
    {
        return Form::name('data')
            ->select('type', ['label' => 'Тип', 'enum' => ContactTypeEnum::class, 'required' => true])
            ->text('value', ['label' => 'Значение', 'required' => true])
            ->textarea('description', ['label' => 'Описание'])
            ->checkbox('main', ['label' => 'Основной']);
    }

    protected function findContact(int $id)
    {
        $contact = ($this->getContactModel())::find($id);
        if (!$contact) {
            return abort(404);
        }
        return $contact;
    }

    protected function route(string $name, int|array $params): string
    {
        return route($this->getPrototypeKey() . '.contacts.' . $name, $params);
    }

    abstract protected function getContactModel(): string;

    abstract protected function getPrototypeKey(): string;
}
