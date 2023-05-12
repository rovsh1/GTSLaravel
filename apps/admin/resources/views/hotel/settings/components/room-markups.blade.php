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
            <td class="column-text">{{ $room->display_name }}</td>
            <td class="column-number">15</td>
            <td class="column-number">15</td>
            <td class="column-number">15</td>
            <td class="column-number">15</td>
            <td class="column-number">15</td>
        </tr>
    @endforeach
    </tbody>
</table>
