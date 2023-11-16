<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Storage\Details;

use Module\Booking\Shared\Domain\Booking\Entity\Other;
use Module\Booking\Shared\Infrastructure\Models\Details\Other as Model;

class OtherServiceStorage extends AbstractStorage
{
    public function store(Other $details): void
    {
        Model::whereId($details->id()->value())->update([
            'data' => [
                'serviceInfo' => $this->serializeServiceInfo($details->serviceInfo()),
                'description' => $details->description(),
            ]
        ]);
    }
}
