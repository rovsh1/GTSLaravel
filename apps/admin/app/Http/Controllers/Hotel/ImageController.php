<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Http\Requests\Hotel\UploadImagesRequest;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Models\Hotel\Image;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Admin\View\Menus\HotelMenu;
use App\Api\Repositories\Hotel\HotelImageRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function __construct(
        private readonly HotelImageRepository $repository
    ) {}

    public function index(Request $request, Hotel $hotel): LayoutContract
    {
        $this->hotel($hotel);

        return Layout::title((string)$hotel)
            ->view('hotel.images.images');
    }

    public function get(Request $request, Hotel $hotel): JsonResponse
    {
        $files = $this->repository->get($hotel->id);

        return response()->json(['images' => $files]);
    }

    public function upload(UploadImagesRequest $request, Hotel $hotel): JsonResponse
    {
        //@todo загружать во временную папку, отдавать путь. А после submit формы, сохранять
        foreach ($request->getFiles() as $file) {
            $this->repository->create($file, $hotel->id);
        }
        return response()->json(['status' => true]);
    }

    public function destroy(Request $request, Hotel $hotel, Image $image): JsonResponse
    {
        $this->repository->delete($image);
        return response()->json(['status' => true]);
    }

    public function reorder(Request $request, Hotel $hotel): JsonResponse
    {
        $files = $request->post('files');
        //@todo сохранить сортировку
        return response()->json(['status' => true]);
    }

    private function hotel(Hotel $hotel): void
    {
        Breadcrumb::prototype('hotel')
            ->addUrl(route('hotels.show', $hotel), (string)$hotel)
            ->addUrl(route('hotels.images.index', $hotel), 'Фотографии');

        Sidebar::submenu(new HotelMenu($hotel, 'images'));
    }
}
