@foreach($detailOptions->chunk(2) as $detailOptionsChunk)
    <tr>
        <td></td>
        @foreach($detailOptionsChunk as $index => $detailOption)
            @if($index === 0)
                <td>{{ $detailOption->label }}: {{ $detailOption->value }}</td>
            @else
                <td colspan="2">{{ $detailOption->label }}: {{ $detailOption->value }}</td>
            @endif
        @endforeach
    </tr>
@endforeach
