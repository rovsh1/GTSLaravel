<?php

namespace App\Admin\Components\Source;

use Illuminate\Database\Eloquent\Model;

class DefaultRepository implements SourceRepositoryInterface
{
    public function __construct(protected readonly string $model) {}

    public function find(int $id): Model
    {
        return $this->model::find($id);
    }

    public function findOrFail(int $id): Model
    {
        return $this->find($id) ?? throw new \Exception('');
    }

    public function create(array $data): Model
    {
        return $this->model::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $model = $this->findOrFail($id);

        return $model->update($data);
    }

    public function delete(int $id): ?bool
    {
        $model = $this->findOrFail($id);

        return $model->delete();
    }
}
