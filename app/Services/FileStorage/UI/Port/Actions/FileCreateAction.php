<?php

namespace GTS\Services\FileStorage\UI\Port\Actions;

use GTS\Services\FileStorage\Infrastructure\Facade\WriterFacadeInterface;

class FileCreateAction
{
    public function __construct(private readonly WriterFacadeInterface $writerFacade) {}

    public function handle($request)
    {
        $file = $this->writerFacade->create($request->fileType, $request->entityId, $request->name, $request->contents);
        dd('ddd', $request, $file);
    }
}
