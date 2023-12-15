<tr class="first last">
    <th class="text-align-left" style="width: 52%;">{{ __('Информация об услуге') }}</th>
</tr>

@if(count($service->guests) > 0)
    <tr class="padding-top">
        <td>{{ __('Гости (:count)', ['count' => count($service->guests)]) }}:</td>
    </tr>
@endif
@foreach($service->guests ?? [] as $index => $guest)
    @include('voucher._partials.guest')
@endforeach

<tr class="padding-top">
    <td>
        <b>{{ $service->title }}</b>
    </td>
</tr>

@include('voucher._partials.details')

@include('voucher._partials.cancel_conditions')
