<?php

declare(strict_types=1);

namespace Module\Booking\Tourist\Infrastructure\Repository;

use Module\Booking\Tourist\Domain\Repository\TouristRepositoryInterface;
use Module\Booking\Tourist\Domain\Tourist;
use Module\Booking\Common\Domain\ValueObject\TouristId;
use Module\Booking\Tourist\Infrastructure\Models\Tourist as Model;
use Module\Shared\Domain\ValueObject\GenderEnum;

class TouristRepository implements TouristRepositoryInterface
{
    public function create(string $fullName, int $countryId, GenderEnum $gender, bool $isAdult, ?int $age): Tourist
    {
        $model = Model::create([
            'name' => $fullName,
            'country_id' => $countryId,
            'gender' => $gender,
            'is_adult' => $isAdult,
            'age' => $age
        ]);

        return $this->buildEntity($model);
    }

    private function buildEntity(Model $model): Tourist
    {
        return new Tourist(
            new TouristId($model->id),
            $model->name,
            $model->country_id,
            $model->gender,
            $model->is_adult,
            $model->age,
        );
    }
}
