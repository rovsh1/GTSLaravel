<tr class="first last">
    <th class="text-align-left" style="width: 52%;">Информация об услуге</th>
    <th style="width: 15%;">Кол-во</th>
    <th style="width: 15%;">Цена, {{ $order->currency }}</th>
    <th style="width: 15%;">Итого, {{ $order->currency }}</th>
</tr>

@if(count($service->guests) > 0)
    <tr class="padding-top">
        <td>Гости ({{ count($service->guests) }}):</td>
        <td></td>
    </tr>
@endif
@foreach($service->guests ?? [] as $index => $guest)
    @include('invoice._partials.guest')
@endforeach

<tr class="padding-top">
    <td colspan="4">
        <b>{{ $service->title }}</b>
    </td>
</tr>

@include('invoice._partials.details')
