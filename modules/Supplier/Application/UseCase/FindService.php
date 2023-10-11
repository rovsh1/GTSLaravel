<?php

declare(strict_types=1);

namespace Module\Supplier\Application\UseCase;

use Module\Supplier\Application\Response\ServiceDto;
use Module\Supplier\Infrastructure\Models\Service;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class FindService implements UseCaseInterface
{
    public function execute(int $id): ?ServiceDto
    {
        $service = Service::find($id);
        if ($service === null) {
            return null;
        }

        return new ServiceDto(
            $service->id,
            $service->title,
            $service->type
        );
    }
}
