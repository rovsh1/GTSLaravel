<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Repository;

use Module\Booking\Shared\Domain\Booking\Entity\DetailsInterface;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Infrastructure\Builder\Details\DetailsBuilderFactory;
use Module\Booking\Shared\Infrastructure\Models\Booking as Model;
use Module\Booking\Shared\Infrastructure\Storage\Details\DetailsStorageFactory;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;
use Sdk\Shared\Support\RepositoryInstances;

class DetailsRepository implements DetailsRepositoryInterface
{
    private RepositoryInstances $instances;

    public function __construct(
        private readonly DetailsBuilderFactory $detailsBuilderFactory,
        private readonly DetailsStorageFactory $detailsStorageFactory,
    ) {
        $this->instances = new RepositoryInstances();
    }

    public function find(BookingId $id): ?DetailsInterface
    {
        if ($this->instances->has($id)) {
            return $this->instances->get($id);
        }

        $model = Model::find($id->value());
        if (null === $model) {
            return null;
        }

        $details = $this->build($model->details);
        $this->instances->add($id, $details);

        return $details;
    }

    public function findOrFail(BookingId $id): DetailsInterface
    {
        return $this->find($id) ?? throw new EntityNotFoundException('Booking details not found');
    }

    public function get(): array
    {
        return $this->instances->all();
    }

    public function store(DetailsInterface $details): void
    {
        $this->detailsStorageFactory->build($details->serviceType())->store($details);
    }

    private function build($details): DetailsInterface
    {
        return $this->detailsBuilderFactory->build($details->serviceType())->build($details);
    }
}
