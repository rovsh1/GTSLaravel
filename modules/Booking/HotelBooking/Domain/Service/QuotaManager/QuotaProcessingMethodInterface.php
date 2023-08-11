<?php

namespace Module\Booking\HotelBooking\Domain\Service\QuotaManager;

use Module\Booking\HotelBooking\Domain\Entity\Booking;

interface QuotaProcessingMethodInterface
{
    //Нужна таблица hotel_room_quota_values - quota_id, type, value, booking_id, context (json), created_at, deleted_at

    //booking_quota_reservation

    //Логика листенера:
    //        - События смены периода
    //        - События смены статуса
    //        - События номеров (добавление/удаление/редактирование)

    //Логика репозитория
    //  -  удалить все записи по броне
    //  -  посчитать кол-во номеров по room_id=date
    //  -  определить тип (booked/reserved)
    //  -  создать новую запись

    //UseCase 1: Юзер оплатил бронь
    //UseCase 2: Бронь подтверждена в админке
    //1. Получаю бронь
    //2. Получаю номера брони
    //3. Для каждого (номера + дата) отменяю все резервы и создаю события списания

    //UseCase 1: Юзер создал бронь на сайте и еще не оплатил
    //UseCase 2: Менеджер добавил или изменил комнаты в броне
    //3. Для каждого (номера + дата) создаю резервы

    //3. Для каждого (номера + дата) отменяю списания

    public function process(Booking $booking): void;
}
