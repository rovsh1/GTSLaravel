<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use Module\Booking\Common\Application\UseCase\SendRequest;

class RequestAdapter
{
    public function sendRequest(int $id): void
    {
        app(SendRequest::class)->execute($id);
    }
}
