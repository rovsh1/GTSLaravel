<?php

namespace Module\Services\FileStorage\Application\Query;

use Custom\Framework\Contracts\Bus\QueryHandlerInterface;
use Custom\Framework\Contracts\Bus\QueryInterface;
use Module\Services\FileStorage\Application\Dto\DataMapper;
use Module\Services\FileStorage\Domain\Repository\DatabaseRepositoryInterface;
use Module\Services\FileStorage\Domain\Service\UrlGeneratorInterface;

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
