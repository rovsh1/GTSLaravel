<?php

namespace Module\Services\FileStorage\Port\Controllers;

use Custom\Framework\Contracts\Bus\CommandBusInterface;
use Custom\Framework\Port\Request;
use Module\Services\FileStorage\Application\Command\CreateFile;
use Module\Services\FileStorage\Application\Dto\DataMapper;
use Module\Services\FileStorage\Application\Dto\FileDto;
use Module\Services\FileStorage\Domain\Service\UrlGeneratorInterface;

class WriteController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {}

    public function create(Request $request): FileDto
    {
        $validated = $request->validate([
            'fileType' => 'required|string',
            'entityId' => 'required|int',
            'name' => 'nullable|string',
            'contents' => 'nullable|string',
        ]);

        $file = $this->commandBus->execute(
            new CreateFile(
                $request->fileType,
                $request->entityId,
                $request->name,
                $request->contents
            )
        );

        return (new DataMapper($this->urlGenerator))->fileToDto($file);
    }

    public function put(string $guid, string $contents): FileDto
    {
        $file = $this->commandBus->execute(new CreateFile());

        return (new DataMapper($this->urlGenerator))->fileToDto($file);
    }

    public function delete(string $guid): bool
    {
        $this->commandBus->execute(new DeleteFile());

        return true;
    }
}
