<?php

namespace Module\Support\FileStorage\Application\Mapper;

use Module\Shared\Dto\FileDto;
use Module\Support\FileStorage\Domain\Entity\File;
use Module\Support\FileStorage\Domain\Service\UrlGeneratorInterface;

class DataMapper
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator
    ) {
    }

    public function fileToDto(File $file): FileDto
    {
        return new FileDto(
            $file->guid()->value(),
            $file->name(),
            $this->urlGenerator->url($file)
        );
    }
}
