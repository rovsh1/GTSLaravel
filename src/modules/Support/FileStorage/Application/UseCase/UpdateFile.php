<?php

namespace Module\Support\FileStorage\Application\UseCase;

use Module\Shared\Dto\FileDto;
use Module\Support\FileStorage\Application\Dto\UpdateFileRequestDto;
use Module\Support\FileStorage\Application\Service\FileUpdater;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateFile implements UseCaseInterface
{
    public function __construct(private readonly FileUpdater $fileUpdater)
    {
    }

    public function execute(UpdateFileRequestDto $request): FileDto
    {
        return $this->fileUpdater->update($request->guid, $request->name, $request->contents);
    }
}
