<?php

namespace Gsdk\Filemanager\Http\Controllers;

use Gsdk\Filemanager\Services\StorageService;
use Gsdk\Filemanager\Services\PathReader;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FilemanagerController extends Controller
{
    protected string $urlPath = '/filemanager/file';

    protected string $noPhotoPath = '/images/nophoto.webp';

    public function __construct(private readonly StorageService $storageService) {}

    public function files(Request $request)
    {
        $path = (string)$request->input('path');

        $page = $request->input('page') ?? 1;
        $step = $request->input('step') ?? 100;
        $offset = ($page - 1) * $step;

        $pathReader = new PathReader($this->storageService);
        $pathReader->read($path, $offset, $step);
        $pathReader->sort();

        //dd($readPath, $path);

        return [
            'urlPath' => $this->urlPath . ($path ? '/' . $path : ''),
            'loadedPath' => $path,
            'step' => $step,
            'files' => $pathReader->files(),
            'count' => $pathReader->count()
        ];
    }

    public function upload(Request $request)
    {
        $path = (string)$request->input('path');

        $this->storageService->cd($path);

        $upload = $request->file('file');
        foreach ($upload as $file) {
            $this->storageService->upload($file);
        }

        return ['uploaded' => 1];
    }

    public function folder(Request $request)
    {
        $path = (string)$request->input('path');
        $name = (string)$request->input('name');

        $this->storageService->cd($path)->mkdir($name);

        return ['name' => $name];
    }

    public function rename(Request $request)
    {
        $path = (string)$request->input('path');
        $prevName = (string)$request->input('file');
        $newName = (string)$request->input('name');

        $this->storageService->cd($path)->rename($prevName, $newName);

        return ['name' => $newName];
    }

    public function move(Request $request)
    {
        $path = (string)$request->input('path');
        $folder = (string)$request->input('folder') ?: '/';

        $this->storageService->cd($path);

        $files = $request->input('files');
        foreach ($files as $name) {
            $this->storageService->moveToPath($name, $folder);
        }

        return ['success' => true];
    }

    public function delete(Request $request)
    {
        $path = (string)$request->input('path');

        $this->storageService->cd($path);

        $files = $request->input('files');

        foreach ($files as $name) {
            $this->storageService->unlink($name, true);
        }

        return ['success' => 1];
    }
}
