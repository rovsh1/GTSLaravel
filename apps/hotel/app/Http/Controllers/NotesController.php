<?php

namespace App\Hotel\Http\Controllers;

use App\Hotel\Services\HotelService;
use App\Hotel\Support\Facades\Layout;
use App\Hotel\Support\View\LayoutBuilder as LayoutContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotesController extends AbstractHotelController
{
    public function __construct(HotelService $hotelService)
    {
        parent::__construct($hotelService);
    }

    public function edit(): LayoutContract
    {
//        Breadcrumb::prototype($this->prototype)
//            ->addUrl($this->prototype->route('show', $hotel), (string)$hotel)
//            ->add('Примечание отеля');
//
//        Sidebar::submenu(new HotelMenu($hotel, 'info'));

        return Layout::title('Изменить примечание отеля')
            ->view('notes.notes', [
                'values' => $this->getHotel()->getTranslations('text'),
                'cancelUrl' => route('hotel.index')
            ]);
    }

    public function update(Request $request): RedirectResponse
    {
        //Массив с тремя языками (ru, en, uz)
        $notes = $request->post('notes');

        $this->getHotel()->update([
            'text' => $notes
        ]);

        return redirect(route('hotel.index'));
    }
}
