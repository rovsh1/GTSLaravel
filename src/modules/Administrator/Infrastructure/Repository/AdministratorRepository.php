<?php

declare(strict_types=1);

namespace Module\Administrator\Infrastructure\Repository;

use Module\Administrator\Domain\Entity\Administrator;
use Module\Administrator\Domain\Repository\AdministratorRepositoryInterface;
use Module\Administrator\Domain\ValueObject\AdministratorId;
use Module\Administrator\Infrastructure\Models\Administrator as Model;

class AdministratorRepository implements AdministratorRepositoryInterface
{
    public function find(int $id): ?Administrator
    {
        $model = Model::whereAdministratorId($id)->first();
        if ($model === null) {
            return null;
        }

        return $this->buildEntity($model);
    }

    public function findByBookingId(int $bookingId): ?Administrator
    {
        $model = Model::whereBookingId($bookingId)->first();
        if ($model === null) {
            return null;
        }

        return $this->buildEntity($model);
    }

    public function findByOrderId(int $orderId): ?Administrator
    {
        $model = Model::whereOrderId($orderId)->first();
        if ($model === null) {
            return null;
        }

        return $this->buildEntity($model);
    }


    private function buildEntity(Model $model): Administrator
    {
        return new Administrator(
            new AdministratorId($model->id),
            $model->presentation,
            $model->email,
            $model->phone,
            $model->post_name,
        );
    }
}
