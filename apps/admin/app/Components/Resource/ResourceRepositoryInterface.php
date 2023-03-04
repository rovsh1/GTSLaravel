<?php

namespace App\Admin\Components\Resource;

use App\Admin\Support\Repository\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

interface ResourceRepositoryInterface extends RepositoryInterface
{
    public function find(int $id): Model;

    public function findOrFail(int $id): Model;

    public function create(array $data): Model;

    public function update(int $id, array $data): bool;

    public function delete(int $id): ?bool;
}
