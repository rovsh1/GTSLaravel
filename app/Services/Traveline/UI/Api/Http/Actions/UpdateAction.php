<?php

namespace GTS\Services\Traveline\UI\Api\Http\Actions;

use GTS\Services\Traveline\Infrastructure\Facade\Hotel\FacadeInterface;
use GTS\Services\Traveline\UI\Api\Http\Requests\UpdateActionRequest;

class UpdateAction
{
    public function __construct(private FacadeInterface $facade) {}

    public function handle(UpdateActionRequest $request)
    {
        //@todo кто подготавливает ответ для тревелайна
        $this->facade->updateQuotasAndPlans();
    }

}
