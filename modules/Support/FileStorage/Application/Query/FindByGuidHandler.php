<?php

namespace Module\Support\FileStorage\Application\Query;

use Module\Support\FileStorage\Application\Dto\DataMapper;
use Module\Support\FileStorage\Application\Dto\FileDto;
use Module\Support\FileStorage\Domain\Repository\CacheRepositoryInterface;
use Module\Support\FileStorage\Domain\Repository\DatabaseRepositoryInterface;
use Module\Support\FileStorage\Domain\Service\UrlGeneratorInterface;
use Sdk\Module\Contracts\Bus\QueryHandlerInterface;
use Sdk\Module\Contracts\Bus\QueryInterface;

class FindByGuidHandler implements QueryHandlerInterface
{
    public function __construct(
        public readonly DatabaseRepositoryInterface $databaseRepository,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly CacheRepositoryInterface $cacheRepository,
    ) {
    }

    public function handle(QueryInterface|FindByGuid $query): ?FileDto
    {
        $file = $this->cacheRepository->get($query->guid) ?? $this->databaseRepository->find($query->guid);
        if (null === $file) {
            return null;
        }

        $this->cacheRepository->store($file);

        return (new DataMapper($this->urlGenerator))->fileToDto($file);
    }
}
