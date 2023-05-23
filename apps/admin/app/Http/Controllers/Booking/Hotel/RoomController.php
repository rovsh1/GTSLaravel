<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Booking\Hotel;

use App\Admin\Support\Facades\Form;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Core\Support\Http\Responses\AjaxReloadResponse;
use App\Core\Support\Http\Responses\AjaxResponseInterface;
use Illuminate\Contracts\View\View;

class RoomController
{
    public function create(int $id): View
    {
        return view('default.dialog-form', [
            'form' => $this->formFactory()
                ->action(route('hotel-booking.rooms.store', $id))
        ]);
    }

    public function store(int $id): AjaxResponseInterface
    {
        return new AjaxReloadResponse();
    }

    protected function formFactory(): FormContract
    {
        return Form::name('data')
            ->select('room_id', [
                'required' => true,
                'label' => 'Номер',
                'emptyItem' => '',
            ])
            ->select('status', [
                'required' => true,
                'label' => 'Статус',
                'emptyItem' => '',
            ])
            ->select('guests_count', [
                'required' => true,
                'label' => 'Кол-во гостей',
                'emptyItem' => '',
            ])
            ->textarea('note', [
                'label' => 'Примечание (запрос в отель, ваучер)',
            ]);
    }
}
