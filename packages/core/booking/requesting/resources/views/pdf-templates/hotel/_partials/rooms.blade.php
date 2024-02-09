@foreach($rooms as $room)
    <div class="service-item">
        <div class="service-guests w-72">
            <p>Гость(и):</p>
            <ol>
                @foreach($room->guests as $index => $guest)
                    <li>
                        @changemark("accommodation[$room->accommodationId].guests")
                        {{++$index}}. {{ $guest->fullName }}, {{ $guest->gender }}, {{ $guest->countryName }}
                        @endchangemark
                    </li>
                @endforeach
            </ol>
        </div>
        <div class="service-details w-72">
            <p><b>Отель: <span>{{ $hotel->name }} ({{ $hotel->city }})</span></b></p>
            <div class="clear-both">
                <div class="column w-45">
                    <p>Дата заезда: <span>{{ $bookingPeriod->startDate }}</span></p>
                </div>
                <div class="column w-55">
                    <p>
                        @changemark("accommodation[$room->accommodationId].earlyCheckIn")
                        Время заезда: с {{ $room->checkInTime }}
                        @endchangemark
                    </p>
                </div>
            </div>
            <div class="clear-both">
                <div class="column w-45">
                    <p>Дата выезда: <span>{{ $bookingPeriod->endDate }}</span></p>
                </div>
                <div class="column w-55">
                    <p>
                        @changemark("accommodation[$room->accommodationId].lateCheckOut")
                        Время выезда: до {{ $room->checkOutTime }}
                        @endchangemark
                    </p>
                </div>
            </div>
            <p>Ночь(ей): <span>{{ $bookingPeriod->nightsCount }}</span></p>
            <p>

                @changemark("accommodation[$room->accommodationId]")
                Номер: {{ $room->name }}
                @endchangemark
            </p>
            <p>
                @changemark("accommodation[$room->accommodationId].rateId")
                Питание: {{ $room->rate }}
                @endchangemark
            </p>
        </div>
        <div class="service-details clear-both">
            <div class="column w-72">
                <p>
                    @changemark("accommodation[$room->accommodationId].guestNote")
                    Примечание (в отель): {{ $room->note }}
                    @endchangemark
                </p>
            </div>
            <div class="column w-28 text-right">{{ Format::price($room->supplierPrice->amount) }} {{ $room->supplierPrice->currency }}</div>
        </div>
    </div>
@endforeach
