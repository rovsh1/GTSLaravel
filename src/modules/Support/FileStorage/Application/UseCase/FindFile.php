<?php

namespace Module\Support\FileStorage\Application\UseCase;

use Module\Support\FileStorage\Application\Mapper\DataMapper;
use Module\Support\FileStorage\Application\Service\FileReader;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Shared\Dto\FileDto;

class FindFile implements UseCaseInterface
{
    public function __construct(
        public readonly FileReader $fileReader,
        private readonly DataMapper $dataMapper,
    ) {
    }

    public function execute(string $guid): ?FileDto
    {
        return ($file = $this->fileReader->findByGuid($guid))
            ? $this->dataMapper->fileToDto($file) : null;
    }
}
