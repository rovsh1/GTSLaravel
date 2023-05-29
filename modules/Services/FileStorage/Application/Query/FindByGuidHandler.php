<?php

namespace Module\Services\FileStorage\Application\Query;

use Module\Services\FileStorage\Application\Dto\DataMapper;
use Module\Services\FileStorage\Application\Dto\FileDto;
use Module\Services\FileStorage\Domain\Repository\DatabaseRepositoryInterface;
use Module\Services\FileStorage\Domain\Service\UrlGeneratorInterface;
use Sdk\Module\Contracts\Bus\QueryHandlerInterface;
use Sdk\Module\Contracts\Bus\QueryInterface;

class FindByGuidHandler implements QueryHandlerInterface
{
    public function __construct(
        public readonly DatabaseRepositoryInterface $databaseRepository,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {}

    public function handle(QueryInterface|FindByGuid $query): ?FileDto
    {
        $file = $this->databaseRepository->find($query->guid);

        return $file ? (new DataMapper($this->urlGenerator))->fileToDto($file) : null;
    }
}
