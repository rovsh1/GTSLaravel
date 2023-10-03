<?php

namespace Module\Booking\Domain\HotelBooking\Service\QuotaManager;

use Module\Booking\Domain\HotelBooking\HotelBooking;

interface QuotaProcessingMethodInterface
{
    //Операции с номерами брони: добавить, удалить, изменить номер | Нужна валидация перед сохранением
    //Измененеие периода брони | Нужна валидация перед сохранением
    //Удаление брони
    //Изменение статуса
    public function process(HotelBooking $booking): void;
}
