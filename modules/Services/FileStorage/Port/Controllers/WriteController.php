<?php

namespace Module\Services\FileStorage\Port\Controllers;

use Module\Services\FileStorage\Application\Command\CreateFile;
use Module\Services\FileStorage\Application\Command\DeleteFile;
use Module\Services\FileStorage\Application\Command\PutFileContents;
use Module\Services\FileStorage\Application\Dto\DataMapper;
use Module\Services\FileStorage\Application\Dto\FileDto;
use Module\Services\FileStorage\Domain\Service\UrlGeneratorInterface;
use Sdk\Module\Contracts\Bus\CommandBusInterface;
use Sdk\Module\PortGateway\Request;

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

    public function put(Request $request): bool
    {
        $validated = $request->validate([
            'guid' => 'required|string',
            'contents' => 'nullable|string',
        ]);

        return $this->commandBus->execute(new PutFileContents($request->guid, $request->contents));
    }

    public function delete(Request $request): bool
    {
        $validated = $request->validate([
            'guid' => 'required|string',
        ]);

        $this->commandBus->execute(new DeleteFile($request->guid));

        return true;
    }
}
