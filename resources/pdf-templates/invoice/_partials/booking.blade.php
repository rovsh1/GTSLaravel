<tr class="first padding-bottom">
    <td class="text-align-center service-name"></td>
    <td class="service-name" colspan="4">{{ $booking->serviceName }}</td>
</tr>

@foreach($booking->rooms ?? [] as $index =>  $room)
    @include('invoice._partials.room')
@endforeach

@foreach($booking->cars ?? [] as $index => $car)
    @include('invoice._partials.car')
@endforeach

@foreach($booking->guests ?? [] as $index => $guest)
    @include('invoice._partials.guest')
@endforeach

@include('invoice._partials.details')

@include('invoice._partials.cancel_conditions')
