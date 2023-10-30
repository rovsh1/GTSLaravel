<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Http\Requests\Hotel\UploadImagesRequest;
use App\Admin\Http\Resources\HotelImage;
use App\Admin\Http\Resources\Room as RoomResource;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Models\Hotel\Image;
use App\Admin\Models\Hotel\Room;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Admin\View\Menus\HotelMenu;
use App\Api\Repositories\Hotel\RoomImageRepository;
use App\Shared\Http\Responses\AjaxResponseInterface;
use App\Shared\Http\Responses\AjaxSuccessResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Module\Catalog\Application\Admin\RequestDto\AddImageRequestDto;
use Module\Catalog\Application\Admin\UseCase\AddImage;
use Module\Catalog\Application\Admin\UseCase\DeleteImage;
use Module\Catalog\Application\Admin\UseCase\GetImages;
use Module\Shared\Dto\UploadedFileDto;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImageController extends Controller
{
    public function __construct(
        private readonly RoomImageRepository $roomImageRepository
    ) {
    }

    public function index(Request $request, Hotel $hotel): LayoutContract
    {
        $roomId = $request->get('room_id');
        if ($roomId !== null) {
            $room = Room::find($roomId);
            if ($room === null) {
                throw new NotFoundHttpException('Room id not found');
            }
            $this->roomMenu($hotel, $room);
        } else {
            $this->hotel($hotel);
        }

        return Layout::title((string)$hotel)
            ->view('hotel.images.images', ['hotel' => $hotel]);
    }

    public function get(Request $request, Hotel $hotel): JsonResponse
    {
        $files = app(GetImages::class)->execute($hotel->id);

        return response()->json($files);
    }

    public function upload(UploadImagesRequest $request, Hotel $hotel): AjaxResponseInterface
    {
        //@todo загружать во временную папку, отдавать путь. А после submit формы, сохранять
        foreach ($request->getFiles() as $file) {
            app(AddImage::class)->execute(
                new AddImageRequestDto(
                    $hotel->id,
                    $request->getRoomId(),
                    UploadedFileDto::fromUploadedFile($file)
                )
            );
        }

        return new AjaxSuccessResponse();
    }

    public function destroy(Request $request, Hotel $hotel, Image $image): AjaxResponseInterface
    {
        app(DeleteImage::class)->execute($hotel->id, $image->id);

        return new AjaxSuccessResponse();
    }

    public function reorder(Request $request, Hotel $hotel): AjaxResponseInterface
    {
        $hotel->updateImageIndexes($request->input('indexes'));

        return new AjaxSuccessResponse();
    }

    public function reorderRoomImages(Request $request, Hotel $hotel, Room $room): AjaxResponseInterface
    {
        $room->updateImageIndexes($request->input('indexes'));

        return new AjaxSuccessResponse();
    }

    public function getRoomImages(Request $request, Hotel $hotel, Room $room): JsonResponse
    {
        $files = $this->roomImageRepository->get($hotel->id, $room->id);

        return response()->json(
            HotelImage::collection($files)
        );
    }

    public function setRoomImage(Request $request, Hotel $hotel, Room $room, Image $image): AjaxResponseInterface
    {
        $this->roomImageRepository->create($image->id, $room->id);

        return new AjaxSuccessResponse();
    }

    public function unsetRoomImage(Request $request, Hotel $hotel, Room $room, Image $image): AjaxResponseInterface
    {
        $this->roomImageRepository->delete($image->id, $room->id);

        return new AjaxSuccessResponse();
    }

    public function getImageRooms(Request $request, Hotel $hotel, Image $image): JsonResponse
    {
        $roomIds = $this->roomImageRepository->getImageRoomIds($hotel->id, $image->id);
        $rooms = RoomResource::collection($hotel->rooms)->toArray($request);
        $rooms = array_map(function (array $roomData) use ($roomIds) {
            $roomData['is_image_linked'] = in_array($roomData['id'], $roomIds);

            return $roomData;
        }, $rooms);

        return response()->json($rooms);
    }

    public function setMainImage(Request $request, Hotel $hotel, Image $image): AjaxResponseInterface
    {
        $image->update(['is_main' => true]);

        return new AjaxSuccessResponse();
    }

    public function unsetMainImage(Request $request, Hotel $hotel, Image $image): AjaxResponseInterface
    {
        $image->update(['is_main' => false]);

        return new AjaxSuccessResponse();
    }

    private function hotel(Hotel $hotel): void
    {
        Breadcrumb::prototype('hotel')
            ->addUrl(route('hotels.show', $hotel), (string)$hotel)
            ->addUrl(route('hotels.images.index', $hotel), 'Фотографии');

        Sidebar::submenu(new HotelMenu($hotel, 'images'));
    }

    private function roomMenu(Hotel $hotel, Room $room): void
    {
        Breadcrumb::prototype('hotel')
            ->addUrl(route('hotels.show', $hotel), (string)$hotel)
            ->addUrl(route('hotels.rooms.index', $hotel), 'Номера')
            ->addUrl(route('hotels.rooms.edit', [$hotel, $room]), (string)$room)
            ->addUrl(route('hotels.images.index', $hotel), 'Фотографии');

        Sidebar::submenu(new HotelMenu($hotel, 'rooms'));
    }
}
