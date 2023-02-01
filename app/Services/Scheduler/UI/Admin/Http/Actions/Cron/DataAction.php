<?php

namespace GTS\Services\Scheduler\UI\Admin\Http\Actions\Cron;

use GTS\Services\Scheduler\Infrastructure\Facade\Cron\CrudFacadeInterface;

class DataAction
{
    public function __construct(
        private readonly CrudFacadeInterface $crudFacade
    ) {}

    public function handle(int $id)
    {
        $cron = $this->crudFacade->find($id);
        if (!$cron)
            return abort(404);

        $data = (array)$cron;
        if ($cron->last_status != 0)
            $data['status_html'] = self::getStatusHtml($cron);

        return $data;
    }
}
