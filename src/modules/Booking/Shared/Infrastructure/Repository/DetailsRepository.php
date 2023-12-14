<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Repository;

use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Module\Booking\Shared\Infrastructure\Builder\Details\DetailsBuilderFactory;
use Module\Booking\Shared\Infrastructure\Models\Booking as Model;
use Module\Booking\Shared\Infrastructure\Storage\Details\DetailsStorageFactory;
use Sdk\Booking\Contracts\Entity\DetailsInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;
use Sdk\Shared\Support\RepositoryInstances;

class DetailsRepository implements DetailsRepositoryInterface
{
    private static RepositoryInstances $instances;

    public function __construct(
        private readonly DetailsBuilderFactory $detailsBuilderFactory,
        private readonly DetailsStorageFactory $detailsStorageFactory,
    ) {
        self::$instances = new RepositoryInstances();
    }

    public function find(BookingId $id): ?DetailsInterface
    {
        if (self::$instances->has($id)) {
            return self::$instances->get($id);
        }

        $model = Model::find($id->value());
        if (null === $model) {
            return null;
        }

        $details = $this->build($model->details);
        self::$instances->add($id, $details);

        return $details;
    }

    public function findOrFail(BookingId $id): DetailsInterface
    {
        return $this->find($id) ?? throw new EntityNotFoundException('Booking details not found');
    }

    public function get(): array
    {
        return self::$instances->all();
    }

    public function store(DetailsInterface $details): void
    {
        $this->detailsStorageFactory->build($details->serviceType())->store($details);
    }

    private function build(mixed $details): DetailsInterface
    {
        return $this->detailsBuilderFactory->build($details->serviceType())->build($details);
    }
}
