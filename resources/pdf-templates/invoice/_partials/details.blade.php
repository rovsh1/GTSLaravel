@foreach($booking->detailOptions as $detailOption)
    <tr>
        <td colspan="4">{{ $detailOption->label }}: {{ $detailOption->getHumanValue() }}</td>
    </tr>
@endforeach
