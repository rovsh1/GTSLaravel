<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\UseCase\Admin;

use Module\Booking\Hotel\Application\Dto\DetailsDto;
use Module\Booking\Hotel\Domain\Repository\DetailsRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class GetDetails implements UseCaseInterface
{
    public function __construct(
        private readonly DetailsRepositoryInterface $detailsRepository
    ) {}

    public function execute(int $id): DetailsDto
    {
        $details = $this->detailsRepository->find($id);
        if ($details === null) {
            throw new EntityNotFoundException("Details not found [{$id}]");
        }

        return DetailsDto::fromDomain($details);
    }
}
