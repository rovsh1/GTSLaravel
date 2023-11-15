<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Application\UseCase;

use Module\Supplier\Moderation\Application\Response\ServiceDto;
use Module\Supplier\Moderation\Infrastructure\Models\Service;
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
