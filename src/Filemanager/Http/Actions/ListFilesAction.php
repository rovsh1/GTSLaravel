<?php

namespace Gsdk\Filemanager\Http\Actions;

use Gsdk\Filemanager\Http\Requests\PathRequest;
use Gsdk\Filemanager\Services\PathReader;

class ListFilesAction
{
    public function handle(PathRequest $request)
    {
        $path = $request->getValidatePath();

        $page = $request->input('page') ?? 1;
        $step = $request->input('step') ?? 100;
        $offset = ($page - 1) * $step;

        $storage = self::storage();
        $readPath = $storage->path($path);

        $pathReader = new PathReader();
        $pathReader->read('', $offset, $step);
        $pathReader->sort();

        //dd($readPath, $path);

        return [
            'relativePath' => self::relativePath . ($path ? '/' . $path : ''),
            'loadedPath' => $path,
            'step' => $step,
            'files' => $pathReader->files(),
            'count' => $pathReader->count()
        ];
    }
}
