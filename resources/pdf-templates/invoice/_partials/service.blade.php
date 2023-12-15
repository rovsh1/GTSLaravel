<tr class="first last">
    <th class="text-align-left" style="width: 52%;">{{ __('Информация об услуге') }}</th>
    <th style="width: 15%;">{{ __('Кол-во') }}</th>
    <th style="width: 15%;">{{ __('Цена') }}</th>
    <th style="width: 15%;">{{ __('Итого') }}</th>
</tr>

@if(count($service->guests) > 0)
    <tr class="padding-top">
        <td>{{ __('Гости (:count)', ['count' => count($service->guests)]) }}:</td>
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
