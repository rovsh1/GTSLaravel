<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Booking\Service;

use App\Core\Support\Http\Responses\AjaxResponseInterface;
use App\Core\Support\Http\Responses\AjaxSuccessResponse;
use Illuminate\Http\Request;

class DetailsController
{
    public function create(Request $request, int $bookingId, int $serviceId): AjaxResponseInterface
    {
        //@todo по-умолчанию создаю детали с дефолтными данными из услуги
        //@todo передаю сырые данные в юзкейз и там дополняю услугу

        //@todo форма создания брони: тип. услуги(фильтр), услуга (service_id)
//        Validator::make(
//            ['service_type' => $serviceType],
//            ['service_type' => ['required', new Enum(ServiceTypeEnum::class)]]
//        )->validate();


        return new AjaxSuccessResponse();
    }

    public function update(Request $request, int $bookingId): AjaxResponseInterface
    {
    }
}
