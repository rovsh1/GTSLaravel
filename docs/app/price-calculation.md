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

### Фиксированные курсы валют для клиентов

Gotostans может устанавливать для отдельных клиентов свой курс валют на определенные отели,
Соответственно стоимость бронирования будет рассчитываться для таких клиентов по установленному курсу

[Admin panel](http://admin.gts.local/client-currency-rates)