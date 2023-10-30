<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Models\Hotel\Contact;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Shared\Http\Responses\AjaxErrorResponse;
use App\Shared\Http\Responses\AjaxRedirectResponse;
use App\Shared\Http\Responses\AjaxResponseInterface;
use Illuminate\Contracts\View\View;
use Module\Shared\Enum\ContactTypeEnum;

class EmployeeContactController
{
    public function create(int $hotelId, int $employeeId): View
    {
        return view('default.dialog-form', [
            'form' => $this->formFactory($hotelId, $employeeId)
                ->action(route('hotels.employee.contacts.store', ['hotel' => $hotelId, 'employee' => $employeeId]))
        ]);
    }

    public function store(int $hotelId, int $employeeId): AjaxResponseInterface
    {
        $form = $this->formFactory($hotelId, $employeeId)
            ->method('post');

        if (!$form->submit()) {
            return new AjaxErrorResponse('');
        }

        $data = $form->getData();
        Contact::create($data);

        return new AjaxRedirectResponse('#contacts');
    }

    public function edit(int $hotelId, int $employeeId, int $contactId): View
    {
        return view('default.dialog-form', [
            'form' => $this->formFactory($hotelId, $employeeId)
                ->method('put')
                ->action(
                    route(
                        'hotels.employee.contacts.update',
                        ['hotel' => $hotelId, 'employee' => $employeeId, 'contact' => $contactId]
                    )
                )
                ->data(
                    Contact::find($contactId)
                )
        ]);
    }

    public function update(int $hotelId, int $employeeId, int $contactId): AjaxResponseInterface
    {
        $form = $this->formFactory($hotelId, $employeeId)
            ->method('put');

        if (!$form->submit()) {
            return new AjaxErrorResponse('');
        }

        Contact::whereHotelId($hotelId)
            ->whereEmployeeId($employeeId)
            ->whereId($contactId)
            ->update($form->getData());

        return new AjaxRedirectResponse('#contacts');
    }

    public function destroy(int $hotelId, int $employeeId, int $contactId): AjaxResponseInterface
    {
        try {
            Contact::whereHotelId($hotelId)
                ->whereEmployeeId($employeeId)
                ->whereId($contactId)
                ->delete();
        } catch (\Throwable $e) {
            return new AjaxErrorResponse('');
        }

        return new AjaxRedirectResponse('#contacts');
    }

    protected function formFactory(int $hotelId, int $employeeId): FormContract
    {
        return Form::name('data')
            ->hidden('hotel_id', ['value' => $hotelId])
            ->hidden('employee_id', ['value' => $employeeId])
            ->enum('type', ['label' => 'Тип', 'enum' => ContactTypeEnum::class, 'required' => true])
            ->text('value', ['label' => 'Значение', 'required' => true])
            ->textarea('description', ['label' => 'Описание'])
            ->checkbox('is_main', ['label' => 'Основной']);
    }
}
