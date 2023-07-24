<?php

namespace Module\Support\FileStorage\Application\UseCase;

use Module\Support\FileStorage\Application\Dto\DataMapper;
use Module\Support\FileStorage\Application\Dto\FileDto;
use Module\Support\FileStorage\Domain\Service\UrlGeneratorInterface;
use Sdk\Module\Contracts\Bus\CommandBusInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class CreateFile implements UseCaseInterface
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly UrlGeneratorInterface $urlGenerator
    ) {
    }

    public function execute(
        string $fileType,
        ?int $entityId,
        ?string $name,
        ?string $contents
    ): FileDto {
        $file = $this->commandBus->execute(
            new \Module\Support\FileStorage\Application\Command\CreateFile(
                $fileType,
                $entityId,
                $name,
                $contents
            )
        );

        return (new DataMapper($this->urlGenerator))->fileToDto($file);
    }
}
