<?php

namespace App\Admin\Repositories;

use App\Admin\Components\Factory\FactoryRepositoryInterface;
use App\Admin\Components\Factory\Support\DefaultRepository;
use App\Admin\Files\TransportImage;
use App\Admin\Models\Reference\TransportCar;
use App\Admin\Support\Http\EntityFileUploader;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TransportCarRepository extends DefaultRepository
{
    public function __construct()
    {
        parent::__construct(TransportCar::class);
    }

    public function create(array $data): ?Model
    {
        $model = $this->model::create($data);
        if (!$model) {
            return null;
        }

        (new EntityFileUploader($model, TransportImage::class))
            ->upload($data['image'] ?? null);

        return $model;
    }

    public function update(int $id, array $data): bool
    {
        $model = $this->findOrFail($id);

        (new EntityFileUploader($model, TransportImage::class))
            ->upload($data['image'] ?? null);

        return $model->update($data);
    }
}
