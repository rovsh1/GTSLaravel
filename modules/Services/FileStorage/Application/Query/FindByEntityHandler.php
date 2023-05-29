<?php

namespace Module\Services\FileStorage\Application\Query;

use Module\Services\FileStorage\Application\Dto\DataMapper;
use Module\Services\FileStorage\Application\Dto\FileDto;
use Module\Services\FileStorage\Domain\Repository\DatabaseRepositoryInterface;
use Module\Services\FileStorage\Domain\Service\UrlGeneratorInterface;
use Sdk\Module\Contracts\Bus\QueryHandlerInterface;
use Sdk\Module\Contracts\Bus\QueryInterface;

class FindByEntityHandler implements QueryHandlerInterface
{
    public function __construct(
        public readonly DatabaseRepositoryInterface $databaseRepository,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) { }

    public function handle(QueryInterface|FindByEntity $query): ?FileDto
    {
        $file = $this->databaseRepository->getEntityFile($query->fileType, $query->entityId);

        return $file ? (new DataMapper($this->urlGenerator))->fileToDto($file) : null;
    }
}
