<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Booking\Order;

use App\Admin\Support\Facades\Booking\Order\VoucherAdapter;
use App\Shared\Http\Responses\AjaxResponseInterface;
use App\Shared\Http\Responses\AjaxSuccessResponse;
use Sdk\Shared\Exception\ApplicationException;

class VoucherController
{
    public function create(int $orderId): AjaxResponseInterface
    {
        try {
            VoucherAdapter::create($orderId);
        } catch (ApplicationException $e) {
            throw $e;
        }

        return new AjaxSuccessResponse();
    }

    public function send(int $orderId): AjaxResponseInterface
    {
        try {
            VoucherAdapter::send($orderId);
        } catch (ApplicationException $e) {
            throw $e;
        }

        return new AjaxSuccessResponse();
    }
}
