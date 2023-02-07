<?php

namespace GTS\Services\FileStorage\UI\Port\Controllers;

use GTS\Services\FileStorage\Application\Dto\FileDto;
use GTS\Services\FileStorage\Infrastructure\Facade\WriterFacadeInterface;

class Controller
{
    public function __construct(
        private readonly WriterFacadeInterface $writerFacade
    ) {}

    public function create(Request $request): FileDto
    {
        return $this->writerFacade->create($request->fileType, $request->entityId, $request->name, $request->contents);
    }
}
