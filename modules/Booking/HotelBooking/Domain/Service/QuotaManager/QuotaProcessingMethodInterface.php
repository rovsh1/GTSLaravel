<?php

namespace Module\Booking\HotelBooking\Domain\Service\QuotaManager;

use Module\Booking\HotelBooking\Domain\Entity\Booking;

interface QuotaProcessingMethodInterface
{
    //Операции с номерами брони: добавить, удалить, изменить номер | Нужна валидация перед сохранением
    //Измененеие периода брони | Нужна валидация перед сохранением
    //Удаление брони
    //Изменение статуса
    public function process(Booking $booking): void;
}
