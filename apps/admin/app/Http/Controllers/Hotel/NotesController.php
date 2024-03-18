<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Prototypes;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Admin\View\Menus\HotelMenu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotesController extends Controller
{
    protected Prototype $prototype;

    public function __construct()
    {
        $this->prototype = Prototypes::get('hotel');
    }

    public function edit(Hotel $hotel): LayoutContract
    {
        Breadcrumb::prototype($this->prototype)
            ->addUrl($this->prototype->route('show', $hotel), (string)$hotel)
            ->add('Текстовое описание отеля');

        Sidebar::submenu(new HotelMenu($hotel, 'info'));

        return Layout::title('Изменить текстовое описание отеля')
            ->view('hotel.notes.notes', [
                'values' => $hotel->getTranslations('text'),
                'cancelUrl' => $this->prototype->route('show', $hotel)
            ]);
    }

    public function update(Request $request, Hotel $hotel): RedirectResponse
    {
        $notes = $request->post('notes');

        $hotel->update([
            'text' => $notes
        ]);

        return redirect($this->prototype->route('show', $hotel));
    }
}
