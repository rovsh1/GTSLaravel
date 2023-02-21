<?php

namespace Module\Services\Scheduler\UI\Admin\Http\Actions\Cron;

use Module\Services\Scheduler\Infrastructure\Facade\Cron\CrudFacadeInterface;

class DeleteAction
{
    public function __construct(
        private readonly CrudFacadeInterface $crudFacade
    ) {}

    public function handle(int $id)
    {
        $this->crudFacade->delete($id);

        return redirect(route('cron.index'));
    }
}
