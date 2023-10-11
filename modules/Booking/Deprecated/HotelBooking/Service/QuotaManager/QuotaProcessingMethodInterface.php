<?php

namespace Module\Booking\Deprecated\HotelBooking\Service\QuotaManager;

use Module\Booking\Deprecated\HotelBooking\HotelBooking;

interface QuotaProcessingMethodInterface
{
    //Операции с номерами брони: добавить, удалить, изменить номер | Нужна валидация перед сохранением
    //Измененеие периода брони | Нужна валидация перед сохранением
    //Удаление брони
    //Изменение статуса
    public function process(HotelBooking $booking): void;
}
