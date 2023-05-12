<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Hotel\Room;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Models\Hotel\Room;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Hotel\MarkupSettingsAdapter;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Admin\View\Menus\HotelMenu;
use Illuminate\Http\RedirectResponse;

class MarkupSettingsController extends Controller
{
    public function edit(Hotel $hotel, Room $room): LayoutContract
    {
        $this->hotel($hotel);

        Breadcrumb::add((string)$room);

        $roomSettings = MarkupSettingsAdapter::getRoomMarkupSettings($hotel->id, $room->id);

        return Layout::title((string)$room)
            ->view('default.form.form', [
                'cancelUrl' => route('hotels.settings.index', $hotel->id),
                'form' => $this->formFactory()
                    ->method('put')
                    ->action(
                        route('hotels.rooms.settings.update', ['hotel' => $hotel->id, 'room' => $room->id])
                    )
                    ->data($roomSettings)
            ]);
    }

    public function update(Hotel $hotel, Room $room): RedirectResponse
    {
        $form = $this->formFactory()
            ->method('put');

        $form->trySubmit(route('hotels.rooms.settings.edit', ['hotel' => $hotel->id, 'room' => $room->id]));

        dd($form->getData());
        MarkupSettingsAdapter::updateMarkupSettings($hotel->id, '', $form->getData());

        return redirect()->route('hotels.settings.index');
    }

    protected function formFactory(): FormContract
    {
        return Form::name('data')
            ->number('discount', ['label' => 'Скидка'])
            ->number('individual', ['label' => 'Физическое лицо', 'required' => true])
            ->number('OTA', ['label' => 'OTA', 'required' => true])
            ->number('TA', ['label' => 'TA', 'required' => true])
            ->number('TO', ['label' => 'TO', 'required' => true]);
    }

    private function hotel(Hotel $hotel): void
    {
        Breadcrumb::prototype('hotel')
            ->addUrl(route('hotels.show', $hotel), (string)$hotel)
            ->addUrl(route('hotels.settings.index', $hotel), 'Условия размещения');

        Sidebar::submenu(new HotelMenu($hotel, 'settings'));
    }
}
