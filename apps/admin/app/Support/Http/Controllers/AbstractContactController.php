<?php

namespace App\Admin\Support\Http\Controllers;

use App\Admin\Enums\ContactTypeEnum;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Core\Support\Http\Responses\AjaxErrorResponse;
use App\Core\Support\Http\Responses\AjaxReloadResponse;
use Illuminate\Contracts\View\View;

abstract class AbstractContactController extends Controller
{
    protected string $contactModel;

    protected string $routePath;

    public function create(int $providerId): View
    {
        return view('default.dialog-form', [
            'form' => $this->formFactory()
                ->action(route($this->routePath . '.contacts.store', $providerId))
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
        $contact = ($this->contactModel)::create($data);

        return new AjaxReloadResponse();
    }

    public function edit(int $id): View
    {
        $contact = $this->findContact($id);

        return view('default.dialog-form', [
            'form' => $this->formFactory()
                ->method('put')
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
        $contact = ($this->contactModel)::find($id);
        if (!$contact) {
            return abort(404);
        }
        return $contact;
    }
}
