<?php

namespace App\Api\Http\Admin\Controllers\V1\Hotel;

use App\Admin\Files\HotelImage;
use App\Admin\Models\Hotel\Hotel;
use App\Api\Http\Admin\Requests\V1\Hotel\UploadImagesRequest;
use App\Core\Http\Controllers\Controller;
use App\Core\Support\Facades\FileAdapter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ImageController extends Controller
{
    public function get(Request $request, Hotel $hotel): JsonResponse
    {
        /** @var Collection<HotelImage>|HotelImage[] $files */
        $files = HotelImage::getEntityFiles($hotel->id);

        return response()->json(['images' => $files]);
    }

    public function upload(UploadImagesRequest $request, Hotel $hotel): JsonResponse
    {
        foreach ($request->getFiles() as $file) {
            HotelImage::create(
                $hotel->id,
                $file->getClientOriginalName(),
                $file->getContent()
            );
        }
        return response()->json(['status' => true]);
    }

    public function destroy(Request $request, Hotel $hotel, string $guid): JsonResponse
    {
        FileAdapter::delete($guid);
        return response()->json(['status' => true]);
    }
}
