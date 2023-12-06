<?php

return [
    // Client
    'Client\\DocumentTypeEnum::CONTRACT' => 'Договор',
    'Client\\DocumentTypeEnum::OTHER' => 'Другие документы',

    'Client\\TypeEnum::PHYSICAL' => 'Физическое лицо',
    'Client\\TypeEnum::LEGAL_ENTITY' => 'Юридическое лицо',

    'Client\\StatusEnum::ACTIVE' => 'Активный',
    'Client\\StatusEnum::BLOCKED' => 'Заблокирован',
    'Client\\StatusEnum::ARCHIVE' => 'Архив',

    'Client\\ResidencyEnum::ALL' => 'Все',
    'Client\\ResidencyEnum::RESIDENT' => 'Резидент',
    'Client\\ResidencyEnum::NONRESIDENT' => 'Не резидент',

    'Contract\\StatusEnum::ACTIVE' => 'Активный',
    'Contract\\StatusEnum::INACTIVE' => 'Неактивный',
    'Contract\\StatusEnum::ARCHIVE' => 'Архив',

    // Supplier
    'Supplier\\ContractServiceTypeEnum::HOTEL' => 'Отель',
    'Supplier\\ContractServiceTypeEnum::AIRPORT' => 'Аэропорт',
    'Supplier\\ContractServiceTypeEnum::TRANSFER' => 'Трансфер',

    // Shared

    'SourceEnum::SITE' => 'Сайт',
    'SourceEnum::ADMIN' => 'Панель администратора',
    'SourceEnum::HOTEL' => 'Панель гостиницы',
    'SourceEnum::API' => 'API',
    'SourceEnum::CONSOLE' => 'Крон',

    'ServiceTypeEnum.DAY_CAR_TRIP' => 'Однодневная поездка',
    'ServiceTypeEnum::INTERCITY_TRANSFER' => 'Междугородный трансфер',
    'ServiceTypeEnum::CAR_RENT_WITH_DRIVER' => 'Аренда авто с водителем',
    'ServiceTypeEnum::TRANSFER_TO_RAILWAY' => 'Трансфер до ж/д вокзала',
    'ServiceTypeEnum::TRANSFER_FROM_RAILWAY' => 'Трансфер от ж/д вокзала',
    'ServiceTypeEnum::TRANSFER_TO_AIRPORT' => 'Трансфер до аэропорта',
    'ServiceTypeEnum::TRANSFER_FROM_AIRPORT' => 'Трансфер от аэропорта',
    'ServiceTypeEnum::MEETING_IN_AIRPORT' => 'Встреча аэропорту',
    'ServiceTypeEnum::SEEING_IN_AIRPORT' => 'Проводы в аэропорт',
    'ServiceTypeEnum::CIP_MEETING_IN_AIRPORT' => 'CIP встреча в аэропорту',
    'ServiceTypeEnum::CIP_SENDOFF_IN_AIRPORT' => 'CIP проводы в аэропорту',
    'ServiceTypeEnum::OTHER_SERVICE' => 'Прочая услуга',

    'GenderEnum::MALE' => 'Мужской',
    'GenderEnum::FEMALE' => 'Женский',

    'ContactTypeEnum::PHONE' => 'Телефон',
    'ContactTypeEnum::EMAIL' => 'Email',
    'ContactTypeEnum::SITE' => 'Сайт',
    'ContactTypeEnum::ADDRESS' => 'Адрес',

    'Pricing\\ValueTypeEnum::PERCENT' => 'Процент',
    'Pricing\\ValueTypeEnum::ABSOLUTE' => 'Значение',

    // Hotel
    'Hotel\\ReviewRatingTypeEnum::STAFF' => 'Персонал',
    'Hotel\\ReviewRatingTypeEnum::FACILITIES' => 'Удобства',
    'Hotel\\ReviewRatingTypeEnum::CLEANNESS' => 'Чистота',
    'Hotel\\ReviewRatingTypeEnum::COMFORT' => 'Комфорт',
    'Hotel\\ReviewRatingTypeEnum::PRICE_QUALITY' => 'Соотношение цена/качество',
    'Hotel\\ReviewRatingTypeEnum::LOCATION' => 'Расположение',
    'Hotel\\ReviewRatingTypeEnum::WIFI' => 'Бесплатный Wi-Fi',
    'Hotel\\ReviewStatusEnum::NOT_PUBLIC' => 'Не опубликовано',
    'Hotel\\ReviewStatusEnum::PUBLIC' => 'Опубликовано',

    'Hotel\\StatusEnum::DRAFT' => 'Черновик',
    'Hotel\\StatusEnum::PUBLISHED' => 'Опубликован',
    'Hotel\\StatusEnum::BLOCKED' => 'Заблокирован',
    'Hotel\\StatusEnum::ARCHIVE' => 'Архив',

    'Hotel\\PriceListStatusEnum::NOT_PROCESSED' => 'Не обработан',
    'Hotel\\PriceListStatusEnum::IN_PROGRESS' => 'В обработке',
    'Hotel\\PriceListStatusEnum::PROCESSED' => 'Обработан',

    'Hotel\\VisibilityEnum::HIDDEN' => 'Скрыт',
    'Hotel\\VisibilityEnum::PUBLIC' => 'Для всех',
    'Hotel\\VisibilityEnum::B2B' => 'B2B',

    'Hotel\\RatingEnum::ONE' => '✯',
    'Hotel\\RatingEnum::TWO' => '✯ ✯',
    'Hotel\\RatingEnum::THREE' => '✯ ✯ ✯',
    'Hotel\\RatingEnum::FOUR' => '✯ ✯ ✯ ✯',
    'Hotel\\RatingEnum::FIVE' => '✯ ✯ ✯ ✯ ✯',

    'Module\Client\Payment\Domain\Payment\ValueObject\PaymentStatusEnum::NOT_PAID' => 'Не распределен',
    'Module\Client\Payment\Domain\Payment\ValueObject\PaymentStatusEnum::PARTIAL_PAID' => 'Частично распределен',
    'Module\Client\Payment\Domain\Payment\ValueObject\PaymentStatusEnum::PAID' => 'Распределен',
    'Module\Client\Payment\Domain\Payment\ValueObject\PaymentStatusEnum::ARCHIVE' => 'Архив',

//    'Module\Booking\Shared\Domain\Shared\Event\Status\BookingCreated' => 'Новая',
//    'Module\Booking\Shared\Domain\Shared\Event\Status\BookingProcessing' => 'В работе',
//    'Module\Booking\Shared\Domain\Shared\Event\Status\BookingCancelled' => 'Отменена',
//    'Module\Booking\Shared\Domain\Shared\Event\Status\BookingConfirmed' => 'Подтверждена',
//    'Module\Booking\Shared\Domain\Shared\Event\Status\BookingNotConfirmed' => 'Не подтверждена',
//    'Module\Booking\Shared\Domain\Shared\Event\Status\BookingInvoiced' => 'Выписан счет',
//    'Module\Booking\Shared\Domain\Shared\Event\Status\BookingPaid' => 'Оплачена',
//    'Module\Booking\Shared\Domain\Shared\Event\Status\BookingPartiallyPaid' => 'Частично оплачена',
//    'Module\Booking\Shared\Domain\Shared\Event\Status\BookingCancelledNoFee' => 'Отмена без штрафа',
//    'Module\Booking\Shared\Domain\Shared\Event\Status\BookingCancelledFee' => 'Отмена со штрафом',
//    'Module\Booking\Shared\Domain\Shared\Event\Status\BookingRefundNoFee' => 'Возврат без штрафа',
//    'Module\Booking\Shared\Domain\Shared\Event\Status\BookingRefundFee' => 'Возврат со штрафом',
//    'Module\Booking\Shared\Domain\Shared\Event\Status\BookingWaitingConfirmation' => 'Ожидает подтверждения',
//    'Module\Booking\Shared\Domain\Shared\Event\Status\BookingWaitingCancellation' => 'Ожидает аннулирования',
//    'Module\Booking\Shared\Domain\Shared\Event\Status\BookingWaitingProcessing' => 'Ожидает обработки',
]; 
 
 