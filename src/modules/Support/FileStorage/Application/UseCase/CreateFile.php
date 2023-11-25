<?php

namespace Module\Support\FileStorage\Application\UseCase;

use Module\Support\FileStorage\Application\Dto\CreateFileRequestDto;
use Module\Support\FileStorage\Application\Service\FileUpdater;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Shared\Dto\FileDto;

class CreateFile implements UseCaseInterface
{
    public function __construct(private readonly FileUpdater $fileUpdater)
    {
    }

    public function execute(CreateFileRequestDto $request): FileDto
    {
        return $this->fileUpdater->create($request->name, $request->contents);
    }
}
