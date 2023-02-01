<?php

namespace GTS\Services\Scheduler\UI\Admin\Http\Actions\Cron;

use GTS\Services\Scheduler\Infrastructure\Facade\Cron\CrudFacadeInterface;

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
