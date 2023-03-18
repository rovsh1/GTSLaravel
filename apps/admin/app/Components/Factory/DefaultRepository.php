<?php

namespace App\Admin\Components\Factory;

use Illuminate\Database\Eloquent\Model;

class DefaultRepository implements FactoryRepositoryInterface
{
    public function __construct(protected readonly string $model) {}

    public function find(int $id): ?Model
    {
        return $this->model::find($id);
    }

    public function findOrFail(int $id): Model
    {
        return $this->find($id) ?? throw new \Exception('');
    }

    public function create(array $data): ?Model
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

    public function query()
    {
        return $this->model::query();
    }

    public function queryWithCriteria(array $criteria = [])
    {
        $query = $this->query();

        $this->applyCriteria($query, $criteria);

        return $query;
    }

    protected function applyCriteria($query, array $criteria)
    {
        if (isset($criteria['quicksearch'])) {
            $query->quicksearch($criteria['quicksearch']);
            unset($criteria['quicksearch']);
        }

        foreach ($criteria as $k => $v) {
            $query->where($k, $v);
        }
    }
}
