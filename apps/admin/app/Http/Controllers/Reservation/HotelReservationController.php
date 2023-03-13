<?php

namespace App\Admin\Http\Controllers\Reservation;

use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form;
use App\Admin\Support\View\Grid\Grid;

class HotelReservationController extends AbstractPrototypeController
{
    protected function gridFactory(): Grid
    {
        return (new Grid())
            ->enableQuicksearch()
            ->paginator(20);
    }

    protected function formFactory(): Form
    {
        return (new Form('data'))
            ->city('city_id', [
                'label' => __('label.city'),
                'emptyItem' => '',
                'required' => true
            ])
            ->client('client_id', [
                'label' => __('label.client'),
                'emptyItem' => '',
                'required' => true
            ])
            ->hidden('legal_id', [
                'label' => 'Юр. лицо',
            ])
            ->hidden('hotel_id', [
                'label' => 'Отель',
                'required' => true
            ])

//            ->addElement('manager_id', 'select', [
//                'label' => 'Менеджер',
//                'default' => App::getUserId(),
//                'textIndex' => 'presentation',
//                'items' => $managers
//            ])
//            ->addElement('period', 'daterange', ['label' => 'Дата заезда/выезда', 'required' => true,])
            //->addElement('date_checkin', 'date', ['required' => true, 'label' => 'Дата заезда'])
            //->addElement('date_checkout', 'date', ['required' => true, 'label' => 'Дата выезда'])
            ->textarea('note', ['label' => 'Примечание']);
    }

    protected function getPrototypeKey(): string
    {
        return 'hotel-reservation';
    }
}
