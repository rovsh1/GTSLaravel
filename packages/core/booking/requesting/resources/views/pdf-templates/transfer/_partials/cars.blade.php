@foreach($cars as $index => $car)
    <div class="service-item">
        <div class="service-title clear-both">
            <div class="column w-55">&nbsp;</div>
            <div class="column w-10 text-center"><b>Кол-во</b></div>
            <div class="column w-17 text-right"><b>Цена</b></div>
            <div class="column w-17 text-right"><b>Итого</b></div>
        </div>
        <div class="service-guests w-55">
            <p>Гость(и):</p>
            <ol>
                @foreach($car->guests as $guest)
                    <li>
                        {{ $guest->fullName }}, {{ $guest->gender }}, {{ $guest->countryName }}
                    </li>
                @endforeach
            </ol>
        </div>
        <div class="service-details w-55">
            @foreach($detailOptions as $detailOption)
                <p>{{ $detailOption->label }}: <b>{{ $detailOption->getHumanValue() }}</b></p>
            @endforeach
            <p>Количество пассажиров: <b>{{ $car->passengersCount }}</b></p>
            <p>Количество багажа: <b>{{ $car->baggageCount > 0 ? $car->baggageCount : 'Без багажа'}}</b></p>
            <p>Марка авто: <b>{{ $car->mark }} {{ $car->model }}</b></p>
        </div>
        <div class="service-details clear-both">
            <div class="column w-55">
                <p>Стоимость услуги:</p>
            </div>
            <div class="column w-10 text-center">
                <b>
                    {{ $car->carsCount }}
                    @if($service->typeId === \Sdk\Shared\Enum\ServiceTypeEnum::CAR_RENT_WITH_DRIVER)
                        * {{ $period->countDays }} {{ trans_choice('[1] день|[2,4] дня|[5,*] дней', $period->countDays) }}
                    @endif
                </b>
            </div>
            <div class="column w-17 text-right">
                <b>{{ Format::number($car->supplierPrice->pricePerCar) }} {{ $car->supplierPrice->currency }}</b></div>
            <div class="column w-17 text-right">
                <b>{{ Format::number($car->supplierPrice->totalAmount) }} {{ $car->supplierPrice->currency }}</b>
            </div>
        </div>
    </div>
@endforeach
