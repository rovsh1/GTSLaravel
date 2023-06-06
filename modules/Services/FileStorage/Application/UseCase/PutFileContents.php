<?php

namespace Module\Services\FileStorage\Application\UseCase;

use Module\Services\FileStorage\Application\Command\PutFileContents as PutCommand;
use Module\Services\FileStorage\Application\Dto\DataMapper;
use Module\Services\FileStorage\Application\Dto\FileDto;
use Module\Services\FileStorage\Domain\Service\UrlGeneratorInterface;
use Sdk\Module\Contracts\Bus\CommandBusInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class PutFileContents implements UseCaseInterface
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly UrlGeneratorInterface $urlGenerator
    ) {
    }

    public function execute(
        string $guid,
        string $contents
    ): FileDto {
        $file = $this->commandBus->execute(
            new PutCommand(
                $guid,
                $contents
            )
        );

        return (new DataMapper($this->urlGenerator))->fileToDto($file);
    }
}
