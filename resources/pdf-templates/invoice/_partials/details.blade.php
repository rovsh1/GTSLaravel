@foreach($booking->detailOptions->chunk(2) as $detailOptionsChunk)
    <tr>
        <td></td>
        @foreach($detailOptionsChunk as $index => $detailOption)
            @if($index % 2 === 0)
                <td>{{ $detailOption->label }}: {{ $detailOption->getHumanValue() }}</td>
            @else
                <td colspan="2">{{ $detailOption->label }}: {{ $detailOption->getHumanValue() }}</td>
            @endif
        @endforeach
    </tr>
@endforeach
