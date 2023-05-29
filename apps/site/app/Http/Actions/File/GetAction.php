<?php

namespace App\Site\Http\Actions\File;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Module\Services\FileStorage\Infrastructure\Facade\ReaderFacadeInterface;
use Sdk\Module\Contracts\PortGateway\PortGatewayInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetAction
{
    protected array $defaultImages = [
        //'/image' => ''
    ];

    public function __construct(
        private readonly PortGatewayInterface $portGateway,
        private readonly Request $request
    ) {}

    public function handle(string $guid, int $part = null)
    {
        $fileInfo = $this->portGateway->request('files/fileInfo', compact('guid', 'part'));
        if (!$fileInfo->exists) {
            return $this->sendNotFound();
        }

        $requestIdentifier = $this->getCacheId($guid, $part);
        if ($this->notModified($fileInfo)) {
            return Response::make()->setNotModified();
        }

        $response = Response::make($this->portGateway->request('files/contents', compact('guid', 'part')));
        $response->headers->add($this->responseHeaders($fileInfo, $requestIdentifier));

        return $response;
    }

    private function getCacheId(string $guid, ?int $part): string
    {
        return 'file.' . $guid . ($part ? '_' . $part : '');
    }

    private function sendNotFound()
    {
        $path = $this->request->getPathInfo();

        foreach ($this->defaultImages as $prefix => $v) {
            if (str_starts_with($path, $prefix)) {
                return $this->renderNotFoundImage(public_path($v));
            }
        }

        throw new NotFoundHttpException('File was not found.');
    }

    private function renderNotFoundImage($destination)
    {
        $response = Response::make(file_get_contents($destination));
        $response->headers->add([
            'Content-Type' => Storage::mimeType($destination),
            'Cache-Control' => 'public'
        ]);
        return $response;
    }

    private function notModified($fileInfo): bool
    {
        return in_array($this->hash($fileInfo), $this->request->getETags());
    }

    private function responseHeaders($fileInfo, string $requestIdentifier)
    {
        $cacheControl =
            (config('renderer.cache.public') ? 'public' : 'private') .
            ',max-age=' . config('renderer.cache.duration');

        return [
            'Content-Type' => $fileInfo->mimeType,
            'Cache-Control' => $cacheControl,
            'ETag' => $this->hash($fileInfo),
        ];
    }

    private function hash($fileInfo): string
    {
        $query = http_build_query($this->request->query());

        return md5($fileInfo->lastModified . '?' . $query);
    }

    private function isImageFilename($filename): bool
    {
        $ext = substr($filename, strrpos($filename, '.'));

        return in_array(strtolower($ext), [
            '.jpg',
            '.jpeg',
            '.svg',
            '.png',
            '.gif',
            '.tiff',
        ]);
    }

//    private function getGuidData(string $guid, ?int $part)
//    {
//        $cacheId = $this->getCacheId($guid, $part);
//        if ($cacheId && Cache::has($cacheId))
//            return Cache::get($cacheId);
//
//        $file = GuidStorageFacade::findByGuid($guid);
//        if (!$file)
//            return null;
//
//        $data = [
//            'name' => $file->name
//        ];
//
//        if ($cacheId)
//            Cache::put($cacheId, $data, 86400);
//
//        return $data;
//    }
}
