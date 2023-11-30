<tr class="first padding-bottom">
{{--    @if($booking->bookingPeriod !== null)--}}
{{--        <td class="service-name">{{ $booking->serviceName }}</td>--}}
{{--        <td colspan="3" class="booking-period">Период бронирования: {{ $booking->bookingPeriod->startDate }}--}}
{{--            - {{ $booking->bookingPeriod->endDate }}--}}
{{--            @if($booking->bookingPeriod->countDays !== null)--}}
{{--                ({{ $booking->bookingPeriod->countDays }})--}}
{{--            @endif--}}
{{--        </td>--}}
{{--    @else--}}
        <td class="service-name" colspan="4">{{ $booking->serviceName }}</td>
{{--    @endif--}}
</tr>

@foreach($booking->rooms ?? [] as $index =>  $room)
    @include('invoice._partials.room')
@endforeach

@foreach($booking->cars ?? [] as $index => $car)
    @include('invoice._partials.car')
@endforeach

@if($booking->rooms === null && $booking->cars === null)
    @include('invoice._partials.booking_price')
@endif

@if(isset($booking->guests))
    <tr>
        <td>Гости ({{ count($booking->guests) }}):</td>
        <td></td>
    </tr>
@endif
@foreach($booking->guests ?? [] as $index => $guest)
    @include('invoice._partials.guest')
@endforeach

@include('invoice._partials.details')

@include('invoice._partials.cancel_conditions')
