<table class="table table-striped">
    <thead>
    <tr>
        <th class="column-edit"></th>
        <th class="column-text">Номер</th>
        <th class="column-number">Скидка</th>
        <th class="column-number">Физическое лицо</th>
        <th class="column-number">OTA</th>
        <th class="column-number">TA</th>
        <th class="column-number">TO</th>
    </tr>
    </thead>
    <tbody>
    @foreach($model->rooms as $room)
        <tr data-id="{{$room->id}}">
            <td class="column-edit">
                <a href="{{ route('hotels.rooms.settings.edit', [$model, $room]) }}"><i class="icon">edit</i></a>
            </td>
            <td class="column-text">{{ $room->name }}</td>
            <td class="column-number">{{ $room->markup_settings?->discount ?? 0 }}</td>
            <td class="column-number">{{ $room->markup_settings?->individual ?? 0 }}</td>
            <td class="column-number">{{ $room->markup_settings?->OTA ?? 0 }}</td>
            <td class="column-number">{{ $room->markup_settings?->TA ?? 0 }}</td>
            <td class="column-number">{{ $room->markup_settings?->TO ?? 0 }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
