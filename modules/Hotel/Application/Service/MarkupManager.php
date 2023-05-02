<?php

declare(strict_types=1);

namespace Module\Hotel\Application\Service;

use Module\Hotel\Application\Dto\MarkupDto;
use Module\Hotel\Domain\Repository\OptionsMarkupRepositoryInterface;

class MarkupManager
{
    public function __construct(
        private readonly OptionsMarkupRepositoryInterface $repository
    ) {}

    /**
     * @param int $hotelId
     * @return MarkupDto[]
     */
    public function getAdditionalConditions(int $hotelId): array
    {
        $conditions = $this->repository->get($hotelId);
        return MarkupDto::collectionFromDomain($conditions);
    }
}
