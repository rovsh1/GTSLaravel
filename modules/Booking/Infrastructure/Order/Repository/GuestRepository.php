<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\Order\Repository;

use Module\Booking\Domain\Order\Entity\Guest;
use Module\Booking\Domain\Order\Repository\GuestRepositoryInterface;
use Module\Booking\Domain\Order\ValueObject\GuestId;
use Module\Booking\Domain\Order\ValueObject\GuestIdsCollection;
use Module\Booking\Domain\Shared\ValueObject\OrderId;
use Module\Booking\Infrastructure\Order\Models\Guest as Model;
use Module\Shared\Enum\GenderEnum;

class GuestRepository implements GuestRepositoryInterface
{
    public function create(
        OrderId $orderId,
        string $fullName,
        int $countryId,
        GenderEnum $gender,
        bool $isAdult,
        ?int $age
    ): Guest {
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

    public function find(GuestId $id): ?Guest
    {
        $model = Model::find($id->value());
        if ($model === null) {
            return null;
        }

        return $this->buildEntity($model);
    }

    /**
     * @param GuestIdsCollection $ids
     * @return array<int, Guest>
     */
    public function get(GuestIdsCollection $ids): array
    {
        $preparedIds = $ids->map(fn(GuestId $id) => $id->value());
        $models = Model::whereIn('id', $preparedIds)->get();

        return $models->map(fn(Model $model) => $this->buildEntity($model))->all();
    }


    public function store(Guest $guest): bool
    {
        return (bool)Model::whereId($guest->id()->value())->update([
            'name' => $guest->fullName(),
            'country_id' => $guest->countryId(),
            'gender' => $guest->gender(),
            'is_adult' => $guest->isAdult(),
            'age' => $guest->age()
        ]);
    }

    public function delete(GuestId $id): void
    {
        Model::whereId($id->value())->delete();
    }


    private function buildEntity(Model $model): Guest
    {
        return new Guest(
            new GuestId($model->id),
            new OrderId($model->order_id),
            $model->name,
            $model->country_id,
            $model->gender,
            $model->is_adult,
            $model->age,
        );
    }
}
