<?php

namespace Module\Support\Scheduler\Infrastructure\Facade\Cron;

use Module\Support\Scheduler\Infrastructure\Models\Cron;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class CrudFacade implements CrudFacadeInterface
{
    public function create(array $data)
    {
        return Cron::create($data);
    }

    public function find(int $id)
    {
        return Cron::find($id);
    }

    public function update(int $id, array $data)
    {
        $cron = $this->findEntity($id);

        $cron->update($data);

        return $cron;
    }

    public function delete(int $id)
    {
        $cron = $this->findEntity($id);

        $deletedDto = (object)$cron->toArray();

        $cron->delete();

        return $deletedDto;
    }

    private function findEntity(int $id)
    {
        $cron = Cron::find($id);
        if (!$cron)
            throw new EntityNotFoundException('Cron entity not found');

        return $cron;
    }
}
