<?php

namespace Module\Support\FileStorage\Application\UseCase;

use Module\Shared\Exception\ApplicationException;
use Module\Support\FileStorage\Application\Dto\FileInfoDto;
use Module\Support\FileStorage\Application\Service\FileReader;
use Module\Support\FileStorage\Domain\Service\PathGeneratorInterface;
use Module\Support\FileStorage\Domain\Service\UrlGeneratorInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetFileInfo implements UseCaseInterface
{
    public function __construct(
        public readonly FileReader $fileReader,
        private readonly PathGeneratorInterface $pathGenerator,
        private readonly UrlGeneratorInterface $urlGenerator
    ) {
    }

    public function execute(string $guid, ?int $part = null): ?FileInfoDto
    {
        $file = $this->fileReader->findByGuid($guid);
        if (!$file) {
            return null;
        }

        $filename = $this->pathGenerator->path($file, $part);

        $exists = file_exists($filename);
        if (!$exists) {
            return null;
//            throw new ApplicationException(ApplicationException::FILE_NOT_FOUND);
        }

        return new FileInfoDto(
            guid: $guid,
            name: $file->name(),
            url: $this->urlGenerator->url($file),
            filename: $filename,
            size: filesize($filename),
            mimeType: finfo_file(finfo_open(FILEINFO_MIME_TYPE), $filename),
            lastModified: filemtime($filename)
        );
    }
}