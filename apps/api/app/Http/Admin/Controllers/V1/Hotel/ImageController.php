<?php

namespace App\Api\Http\Admin\Controllers\V1\Hotel;

use App\Admin\Models\Hotel\Hotel;
use App\Admin\Models\Hotel\Image;
use App\Api\Http\Admin\Requests\V1\Hotel\UploadImagesRequest;
use App\Api\Repositories\Hotel\HotelImageRepository;
use App\Core\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function __construct(
        private readonly HotelImageRepository $repository
    ) {}

    public function get(Request $request, Hotel $hotel): JsonResponse
    {
        $files = $this->repository->get($hotel->id);

        return response()->json(['images' => $files]);
    }

    public function upload(UploadImagesRequest $request, Hotel $hotel): JsonResponse
    {
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
}
