<?php

namespace Module\Support\Scheduler\UI\Admin\Http\Actions\Cron;

use Module\Support\Scheduler\Infrastructure\Facade\Cron\CrudFacadeInterface;

class RunAction
{
    public function __construct(
        private readonly CrudFacadeInterface $crudFacade
    ) {}

    public function handle(int $id)
    {
        $cron = $this->crudFacade->find($id);
        if (!$cron)
            return abort(404);

        //CronService::runBackground($cron);

        return ['status' => 'ok'];
    }
}
