<?php

namespace App\Admin\Repositories;

use App\Admin\Components\Factory\Support\DefaultRepository;
use App\Admin\Models\Reference\TransportCar;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Sdk\Shared\Contracts\Adapter\FileStorageAdapterInterface;

class TransportCarRepository extends DefaultRepository
{
    public function __construct(
        private readonly FileStorageAdapterInterface $fileStorageAdapter
    ) {
        parent::__construct(TransportCar::class);
    }

    public function create(array $data): ?Model
    {
        $model = new TransportCar();
        $model->fill($this->prepareData($model, $data));
        $model->save();

        return $model;
    }

    public function update(int $id, array $data): bool
    {
        $model = $this->findOrFail($id);

        return $model->update($this->prepareData($model, $data));
    }

    private function prepareData(TransportCar $model, array $data): array
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $data['image'] ?? null;

        if ($uploadedFile) {
            $fileDto = $this->fileStorageAdapter->updateOrCreate(
                $model->image?->guid,
                $uploadedFile->getClientOriginalName(),
                $uploadedFile->get()
            );
            $data['image'] = $fileDto?->guid;
        } else {
            $data['image'] = null;
        }

        return $data;
    }
}
