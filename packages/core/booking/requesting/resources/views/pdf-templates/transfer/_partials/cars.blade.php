@foreach($cars as $index => $car)
    <div class="service-item">
        <div class="service-title clear-both">
            <div class="column w-55">&nbsp;</div>
            <div class="column w-10 text-center"><b>Кол-во</b></div>
            <div class="column w-17 text-right"><b>Цена</b></div>
            <div class="column w-17 text-right"><b>Итого</b></div>
        </div>
        <div class="service-guests w-55">
            @changemark('carBid[$car->carBidId].guests')
                <p>Гость(и):</p>
            @endchangemark()
            <ol>
                @foreach($car->guests as $guest)
                    <li>
                        @changemark('carBid[$car->carBidId].guests')
                            {{ $guest->fullName }}, {{ $guest->gender }}, {{ $guest->countryName }}
                        @endchangemark()
                    </li>
                @endforeach
            </ol>
        </div>
        <div class="service-details w-55">
            @foreach($detailOptions as $detailOption)
                @if($detailOption->key !== null)
                    @changemark($detailOption->key)
                        <p>{{ $detailOption->label }}: <b>{{ $detailOption->getHumanValue() }}</b></p>
                    @endchangemark()
                @else
                    <p>{{ $detailOption->label }}: <b>{{ $detailOption->getHumanValue() }}</b></p>
                @endif
            @endforeach

            @changemark("carBid[$car->carBidId].passengersCount")
                <p>Количество пассажиров: <b>{{ $car->passengersCount }}</b></p>
            @endchangemark()

            @changemark("carBid[$car->carBidId].baggageCount")
                <p>Количество багажа: <b>{{ $car->baggageCount > 0 ? $car->baggageCount : 'Без багажа'}}</b></p>
            @endchangemark()

            @changemark("carBid[$car->carBidId]")
                <p>Марка авто: <b>{{ $car->mark }} {{ $car->model }}</b></p>
            @endchangemark()
        </div>
        <div class="service-details clear-both">
            <div class="column w-55">
                <p>Стоимость услуги:</p>
            </div>
            <div class="column w-10 text-center">
                <b>
                    @changemark("carBid[$car->carBidId].carsCount")
                        {{ $car->carsCount }}

                        @if($service->typeId === \Sdk\Shared\Enum\ServiceTypeEnum::CAR_RENT_WITH_DRIVER)
                            * {{ $period->countDays }} {{ trans_choice('[1] день|[2,4] дня|[5,*] дней', $period->countDays) }}
                        @endif
                    @endchangemark()
                </b>
            </div>
            <div class="column w-17 text-right">
                <b>{{ Format::number($car->supplierPrice->pricePerCar) }} {{ $car->supplierPrice->currency }}</b></div>
            <div class="column w-17 text-right">
                <b>{{ Format::number($car->supplierPrice->totalAmount) }} {{ $car->supplierPrice->currency }}</b>
            </div>
        </div>

        @haschanges("carBid[$car->carBidId]")
        <div>
            <p style="color: blue; font-weight: bold">Изменения:</p>
            <ul style="margin-top: 3px; padding-inline-start: 25px; color: blue; font-weight: bold">
                @changes("carBid[$car->carBidId]")
                <li>{{ $change->description() }}</li>
                @endchanges
            </ul>
        </div>
        @endhaschanges
    </div>
@endforeach
