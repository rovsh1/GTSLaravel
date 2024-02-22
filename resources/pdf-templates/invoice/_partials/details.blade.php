<div class="service-details w-55">
    <p><b>{{ $service->title }}</b></p>
    @foreach($service->detailOptions as $index => $detailOption)
        @if($index < $service->detailOptions->count() - 1)
            <p>{{ $detailOption->label }}: {{ $detailOption->getHumanValue() }}</p>
        @endif
    @endforeach
</div>

@php
    $lastDetailOption = $service->detailOptions->last();
@endphp

<div class="service-details clear-both">
    <div class="column w-55">
        <p>{{ $lastDetailOption->label }}: {{ $lastDetailOption->getHumanValue() }}</p>
    </div>
    <div class="column w-10 text-center">{{ $service->price->quantity }}</div>
    <div class="column w-17 text-right">
        @if(empty($invoice->totalPenalty))
            {{ Format::number($service->price->amount) }} {{ $order->currency }}
        @endif
    </div>
    <div class="column w-17 text-right">
        @if(empty($invoice->totalPenalty))
            {{ Format::number($service->price->total) }} {{ $order->currency }}
        @endif
    </div>
</div>
