<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Booking\Hotel;

use App\Admin\Support\Facades\Booking\RequestAdapter;
use App\Core\Support\Http\Responses\AjaxResponseInterface;
use App\Core\Support\Http\Responses\AjaxSuccessResponse;

class RequestController
{
    public function sendRequest(int $id): AjaxResponseInterface
    {
        try {
            RequestAdapter::sendRequest($id);
        } catch (\Throwable $e) {
            //@todo отлов доменных эксепшнов
            dd($e);
        }
        return new AjaxSuccessResponse();
    }

}
