@foreach($booking->detailOptions as $detailOption)
    <tr>
        <td></td>
        <td colspan="4">{{ $detailOption->label }}: {{ $detailOption->getHumanValue() }}</td>
    </tr>
@endforeach
