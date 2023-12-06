@foreach($service->detailOptions as $index => $detailOption)
    <tr>
        <td>{{ $detailOption->label }}: {{ $detailOption->getHumanValue() }} </td>
{{--        <td>Статус: Подтверждена</td>--}}
    </tr>
@endforeach
