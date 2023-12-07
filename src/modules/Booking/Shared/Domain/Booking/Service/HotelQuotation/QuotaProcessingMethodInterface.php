<?php

namespace Module\Booking\Shared\Domain\Booking\Service\HotelQuotation;

use Module\Booking\Shared\Domain\Booking\Booking;
use Sdk\Booking\Entity\Details\HotelBooking;

interface QuotaProcessingMethodInterface
{
    //Операции с номерами брони: добавить, удалить, изменить номер | Нужна валидация перед сохранением
    //Измененеие периода брони | Нужна валидация перед сохранением
    //Удаление брони
    //Изменение статуса
    public function process(Booking $booking, HotelBooking $details): void;
}