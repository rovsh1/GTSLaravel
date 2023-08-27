<?php

declare(strict_types=1);

namespace Module\Booking\Order\Infrastructure\Repository;

use Module\Booking\Common\Domain\ValueObject\OrderId;
use Module\Booking\Order\Domain\Entity\Tourist;
use Module\Booking\Order\Domain\Repository\TouristRepositoryInterface;
use Module\Booking\Order\Domain\ValueObject\TouristId;
use Module\Booking\Order\Infrastructure\Models\Tourist as Model;
use Module\Shared\Domain\ValueObject\GenderEnum;

class TouristRepository implements TouristRepositoryInterface
{
    public function create(
        OrderId $orderId,
        string $fullName,
        int $countryId,
        GenderEnum $gender,
        bool $isAdult,
        ?int $age
    ): Tourist {
        $model = Model::create([
            'name' => $fullName,
            'order_id' => $orderId->value(),
            'country_id' => $countryId,
            'gender' => $gender,
            'is_adult' => $isAdult,
            'age' => $age
        ]);

        return $this->buildEntity($model);
    }

    public function find(TouristId $id): ?Tourist
    {
        $model = Model::find($id->value());
        if ($model === null) {
            return null;
        }

        return $this->buildEntity($model);
    }

    public function store(Tourist $tourist): bool
    {
        return (bool)Model::whereId($tourist->id()->value())->update([
            'name' => $tourist->fullName(),
            'country_id' => $tourist->countryId(),
            'gender' => $tourist->gender(),
            'is_adult' => $tourist->isAdult(),
            'age' => $tourist->age()
        ]);
    }

    public function delete(TouristId $id): void
    {
        Model::whereId($id->value())->delete();
    }


    private function buildEntity(Model $model): Tourist
    {
        return new Tourist(
            new TouristId($model->id),
            new OrderId($model->order_id),
            $model->name,
            $model->country_id,
            $model->gender,
            $model->is_adult,
            $model->age,
        );
    }
}
