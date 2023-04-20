<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Models\Reference\Landmark;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Admin\View\Menus\HotelMenu;
use App\Core\Support\Http\Responses\AjaxRedirectResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function index(Request $request, Hotel $hotel): LayoutContract
    {
        $this->hotel($hotel);

        //        $query = Employee::whereHotelId($hotel->id);
        //        $grid = $this->gridFactory($hotel->id)->data($query);

        return Layout::title((string)$hotel)
            ->view('hotel.images.images', [
                'hotel' => $hotel,
                'editAllowed' => $this->isUpdateAllowed(),
                'deleteAllowed' => $this->isUpdateAllowed(),
                'createUrl' => $this->isUpdateAllowed() ? route('hotels.images.store', $hotel) : null,
            ]);
    }

    public function store(Request $request, Hotel $hotel): RedirectResponse
    {
        $files = $request->allFiles();

        return new AjaxRedirectResponse();
    }

    protected function formFactory(int $hotelId, int $cityId): FormContract
    {
        return Form::name('data')
            ->hidden('hotel_id', ['value' => $hotelId])
            ->select('landmark_id', [
                'label' => 'Объект',
                'required' => true,
                'items' => Landmark::whereCityId($cityId)->get(),
                'emptyItem' => '',
            ]);
    }

    private function hotel(Hotel $hotel): void
    {
        Breadcrumb::prototype('hotel')
            ->addUrl(route('hotels.show', $hotel), (string)$hotel)
            ->addUrl(route('hotels.images.index', $hotel), 'Фотографии');

        Sidebar::submenu(new HotelMenu($hotel, 'images'));
    }

    private function isUpdateAllowed(): bool
    {
        return Acl::isUpdateAllowed('hotel');
    }
}
