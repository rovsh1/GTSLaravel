@props(['collapsable' => false])

<x-ui.card id="card-services" header="Дополнительные параметры" collapsable="{{$collapsable}}">
    <div class="d-flex gap-3">
        <div class="card flex-grow-1 flex-basis-200">
            <div class="card-body">
                <h5 class="card-title">
                    Бесплатные услуги
                    <a class="btn-edit btn-services" href="#">Изменить</a>
                </h5>
                @if($services->where('is_paid', false)->isEmpty())
                    <i class="empty">Отсутствуют</i>
                @else
                    <ul class="hotel-services">
                        @foreach($services->where('is_paid', false) as $service)
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
                    <a class="btn-edit btn-services" href="#">Изменить</a>
                </h5>
                @if($services->where('is_paid', true)->isEmpty())
                    <i class="empty">Отсутствуют</i>
                @else
                    <ul class="hotel-services">
                        @foreach($services->where('is_paid', true) as $service)
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
                    <a class="btn-edit btn-services" href="#">Изменить</a>
                </h5>
                @if($usabilities->isEmpty())
                    <i class="empty">Отсутствуют</i>
                @else
                    <ul class="hotel-services">
                        @foreach($usabilities as $usability)
                            <li>{{ $usability->name }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-ui.card>
