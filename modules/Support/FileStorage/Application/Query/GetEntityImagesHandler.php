<?php

namespace Module\Support\FileStorage\Application\Query;

use Module\Support\FileStorage\Application\Dto\DataMapper;
use Module\Support\FileStorage\Domain\Repository\DatabaseRepositoryInterface;
use Module\Support\FileStorage\Domain\Service\UrlGeneratorInterface;
use Sdk\Module\Contracts\Bus\QueryHandlerInterface;
use Sdk\Module\Contracts\Bus\QueryInterface;

class GetEntityImagesHandler implements QueryHandlerInterface
{
    public function __construct(
        public readonly DatabaseRepositoryInterface $databaseRepository,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {}

    public function handle(QueryInterface|GetEntityImages $query): array
    {
        $mapper = new DataMapper($this->urlGenerator);

        return array_map(fn($r) => $mapper->fileToDto($r), $this->databaseRepository->getEntityFiles($query->fileType, $query->entityId));
    }
}
