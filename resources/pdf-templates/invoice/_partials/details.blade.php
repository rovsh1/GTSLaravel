@foreach($service->detailOptions as $index => $detailOption)
    <tr>
        @if($index === $service->detailOptions->count() - 1)
            <td>{{ $detailOption->label }}: {{ $detailOption->getHumanValue() }}</td>
            <td class="text-align-center" style="padding: 5px 0;">{{ $service->price->quantity }}</td>
            <td class="text-align-center" style="padding: 5px 0;">
                {{ Format::number($service->price->amount) }} {{ $order->currency }}
            </td>
            <td class="text-align-center" style="padding: 5px 0;">
                {{ Format::number($service->price->total) }} {{ $order->currency }}
            </td>
        @else
            <td colspan="4">{{ $detailOption->label }}: {{ $detailOption->getHumanValue() }}</td>
        @endif
    </tr>
@endforeach
