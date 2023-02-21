<?php

namespace Module\Services\FileStorage\Port\Controllers;

use Custom\Framework\Port\Request;
use Module\Services\FileStorage\Application\Dto\FileDto;
use Module\Services\FileStorage\Infrastructure\Facade\WriterFacadeInterface;

class Controller
{
    public function __construct(
        private readonly WriterFacadeInterface $writerFacade
    ) {}

    public function create(Request $request): FileDto
    {
        $validated = $request->validate([
            'fileType' => 'required|string',
            'entityId' => 'required|int',
            'name' => 'nullable|string',
            'contents' => 'nullable|string',
        ]);

        return $this->writerFacade->create($request->fileType, $request->entityId, $request->name, $request->contents);
    }
}
