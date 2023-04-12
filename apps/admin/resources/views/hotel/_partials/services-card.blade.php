@props(['collapsable' => false])

<x-ui.card id="card-services" header="Дополнительные параметры" collapsable="{{$collapsable}}">
    <div class="d-flex gap-3">
        <div class="card flex-grow-1 flex-basis-200">
            <div class="card-body">
                <h5 class="card-title">
                    Бесплатные услуги
                    @if($servicesEditable)
                        <a class="btn-edit btn-services" href="#" data-url="{{$servicesUrl}}">Изменить</a>
                    @endif
                </h5>
                @if($hotelServices->where('is_paid', false)->isEmpty())
                    <i class="empty">Отсутствуют</i>
                @else
                    <ul class="hotel-services">
                        @foreach($hotelServices->where('is_paid', false) as $service)
                            <li>{{ $service->name }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
        <div class="card flex-grow-1 flex-basis-200">
            <div class="card-body">
                <h5 class="card-title">
                    Платные услуги
                    @if($servicesEditable)
                        <a class="btn-edit btn-services" href="#" data-url="{{$servicesUrl}}">Изменить</a>
                    @endif
                </h5>
                @if($hotelServices->where('is_paid', true)->isEmpty())
                    <i class="empty">Отсутствуют</i>
                @else
                    <ul class="hotel-services">
                        @foreach($hotelServices->where('is_paid', true) as $service)
                            <li>{{ $service->name }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
        <div class="card flex-grow-1 flex-basis-200">
            <div class="card-body">
                <h5 class="card-title">
                    Удобства
                    @if($usabilitiesEditable)
                        <a class="btn-edit btn-usabilities" href="#" data-url="{{$usabilitiesUrl}}">Изменить</a>
                    @endif
                </h5>
                @if($hotelUsabilities->isEmpty())
                    <i class="empty">Отсутствуют</i>
                @else
                    <ul class="hotel-services">
                        @foreach($hotelUsabilities as $usability)
                            <li>{{ $usability->name }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-ui.card>
