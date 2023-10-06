# Рассчет стоимости бронирования

## Бронирование отеля

### Переменные и условия для расчета стоимости бронирования отеля

```
HotelMarkupOptions
    --vat (Percent)
    --tourCharge (Percent)
    --clientMarkups
        --individual (Percent)
        --TA (Percent)
        --OTA (Percent)
        --OT (Percent)
    --earlyCheckIn (EarlyCheckInCollection)
        --Condition
            --time (TimePeriod)
            --markup (Percent)
    --lateCheckOut (LateCheckOutCollection)
        --//--
    --cancelPeriods (CancelPeriodsCollection)
        --CancelPeriod
            --period (Period)
            --noCheckInMarkup (CancelMarkupPercent)
                --value (Pervent)
                --periodTime (BookingPeriodEnum)
            --dailyMarkups (collection)
                --daysCount (int)
                --markup (CancelMarkupPercent)
```

## Формула расчета для отельных броней

```
Pb  = Pr + Z(Pr)
Pr* = Pr + N(Po)
Pr  = Po + NDS(Po) + T(BZV) * n

Pb  - Итоговая стоимость бронирования за день
Pr  - Стоимость номера за день (* Брутто)
Po  - Базовая стоимость (из таблицы)
Z   - Наценка за ранний заезд/поздний выезд (% от Pr)
N   - Наценка GTS (от Po, из конфигуратора на клиентов)
NDS - НДС (% от Po, из настроек отеля)
T   - Турсбора (% от BZV, зависит от резидентства)
n   - Кол-во гостей
BZV - Базовая расчетная величина (Константа в настройках)
```

### Фиксированные курсы валют для клиентов

Gotostans может устанавливать для отдельных клиентов свой курс валют на определенные отели,
Соответственно стоимость бронирования будет рассчитываться для таких клиентов по установленному курсу

[Admin panel](http://admin.gts.local/client-currency-rates)