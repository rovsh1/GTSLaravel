<?php

namespace App\Api\Http\Admin\Controllers\V1\Hotel;

use App\Admin\Models\Hotel\Hotel;
use App\Core\Http\Controllers\Controller;
use Custom\Framework\Contracts\PortGateway\PortGatewayInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function __construct(
        private readonly PortGatewayInterface $portGateway
    ) {}

    public function get(Request $request, Hotel $hotel): JsonResponse
    {
        $files = $this->portGateway->request('fileStorage/getEntityFiles', [
            'fileType' => 'image',
            'entityId' => $hotel->id
        ]);

        return response()->json(['images' => $files]);
    }

    public function upload(Request $request): JsonResponse
    {
        return response()->json(['status' => true]);
    }
}
