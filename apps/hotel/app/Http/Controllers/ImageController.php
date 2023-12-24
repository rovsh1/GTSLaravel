<?php

namespace App\Hotel\Http\Controllers;

use App\Api\Repositories\Hotel\RoomImageRepository;
use App\Hotel\Http\Requests\UploadImagesRequest;
use App\Hotel\Http\Resources\HotelImage;
use App\Hotel\Http\Resources\Room as RoomResource;
use App\Hotel\Models\Image;
use App\Hotel\Models\Room;
use App\Hotel\Services\HotelService;
use App\Hotel\Support\Facades\Layout;
use App\Hotel\Support\View\LayoutBuilder as LayoutContract;
use App\Shared\Http\Responses\AjaxResponseInterface;
use App\Shared\Http\Responses\AjaxSuccessResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Module\Hotel\Moderation\Application\RequestDto\AddImageRequestDto;
use Module\Hotel\Moderation\Application\UseCase\AddImage;
use Module\Hotel\Moderation\Application\UseCase\DeleteImage;
use Module\Hotel\Moderation\Application\UseCase\GetImages;
use Sdk\Shared\Dto\UploadedFileDto;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImageController extends AbstractHotelController
{
    public function __construct(
        HotelService $hotelService,
        private readonly RoomImageRepository $roomImageRepository
    ) {
        parent::__construct($hotelService);
    }

    public function index(Request $request): LayoutContract
    {
        $roomId = $request->get('room_id');
        if ($roomId !== null) {
            $room = Room::find($roomId);
            if ($room === null) {
                throw new NotFoundHttpException('Room id not found');
            }
        }

        return Layout::title($room?->name ?? 'Фотографии')
            ->view('images.images', ['hotel' => $this->getHotel()]);
    }

    public function get(Request $request): JsonResponse
    {
        $files = app(GetImages::class)->execute($this->getHotel()->id);

        return response()->json($files);
    }

    public function upload(UploadImagesRequest $request): AjaxResponseInterface
    {
        //@todo загружать во временную папку, отдавать путь. А после submit формы, сохранять
        foreach ($request->getFiles() as $file) {
            app(AddImage::class)->execute(
                new AddImageRequestDto(
                    $this->getHotel()->id,
                    $request->getRoomId(),
                    UploadedFileDto::fromUploadedFile($file)
                )
            );
        }

        return new AjaxSuccessResponse();
    }

    public function destroy(Request $request, Image $image): AjaxResponseInterface
    {
        app(DeleteImage::class)->execute($this->getHotel()->id, $image->id);

        return new AjaxSuccessResponse();
    }

    public function reorder(Request $request): AjaxResponseInterface
    {
        $this->getHotel()->updateImageIndexes($request->input('indexes'));

        return new AjaxSuccessResponse();
    }

    public function reorderRoomImages(Request $request, Room $room): AjaxResponseInterface
    {
        $room->updateImageIndexes($request->input('indexes'));

        return new AjaxSuccessResponse();
    }

    public function getRoomImages(Request $request, Room $room): JsonResponse
    {
        $files = $this->roomImageRepository->get($this->getHotel()->id, $room->id);

        return response()->json(
            HotelImage::collection($files)
        );
    }

    public function setRoomImage(Request $request, Room $room, Image $image): AjaxResponseInterface
    {
        $this->roomImageRepository->create($image->id, $room->id);

        return new AjaxSuccessResponse();
    }

    public function unsetRoomImage(Request $request, Room $room, Image $image): AjaxResponseInterface
    {
        $this->roomImageRepository->delete($image->id, $room->id);

        return new AjaxSuccessResponse();
    }

    public function getImageRooms(Request $request, Image $image): JsonResponse
    {
        $roomIds = $this->roomImageRepository->getImageRoomIds($this->getHotel()->id, $image->id);
        $rooms = RoomResource::collection($this->getHotel()->rooms)->toArray($request);
        $rooms = array_map(function (array $roomData) use ($roomIds) {
            $roomData['is_image_linked'] = in_array($roomData['id'], $roomIds);

            return $roomData;
        }, $rooms);

        return response()->json($rooms);
    }

    public function setMainImage(Request $request, Image $image): AjaxResponseInterface
    {
        $image->update(['is_main' => true]);

        return new AjaxSuccessResponse();
    }

    public function unsetMainImage(Request $request, Image $image): AjaxResponseInterface
    {
        $image->update(['is_main' => false]);

        return new AjaxSuccessResponse();
    }
}
