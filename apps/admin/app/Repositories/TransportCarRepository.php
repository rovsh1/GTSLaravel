<?php

namespace App\Admin\Repositories;

use App\Admin\Components\Factory\Support\DefaultRepository;
use App\Admin\Models\Reference\TransportCar;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Module\Shared\Contracts\Adapter\FileStorageAdapterInterface;

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
        $this->storeImage($model, $data['image'] ?? null);
        unset($data['image']);
        $model->fill($data);
        $model->save();

        return $model;
    }

    public function update(int $id, array $data): bool
    {
        $model = $this->findOrFail($id);

        $this->storeImage($model, $data['image'] ?? null);
        unset($data['image']);

        return $model->update($data);
    }

    private function storeImage(TransportCar $model, ?UploadedFile $uploadedFile): void
    {
        if ($model->image_guid) {
            $this->fileStorageAdapter->update(
                $model->image_guid,
                $uploadedFile->getClientOriginalName(),
                $uploadedFile->get()
            );
        } else {
            $fileDto = $this->fileStorageAdapter->create(
                $uploadedFile->getClientOriginalName(),
                $uploadedFile->get()
            );
            $model->image_guid = $fileDto->guid;
        }
    }
}
