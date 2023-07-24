<?php

namespace Module\Support\FileStorage\Application\Query;

use Module\Support\FileStorage\Application\Dto\DataMapper;
use Module\Support\FileStorage\Application\Dto\FileDto;
use Module\Support\FileStorage\Domain\Repository\DatabaseRepositoryInterface;
use Module\Support\FileStorage\Domain\Service\UrlGeneratorInterface;
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
