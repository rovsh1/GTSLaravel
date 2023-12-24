@extends('mail-layout')

@section('content')
    <table>
        <tr>
            <td>{{ __('Уважаемый :client, добрый день!', ['client' => $client->name]) }}</td>
        </tr>

        <tr>
            <td>{{ __('Ваши брони подтверждены, отправляем Вам ваучер – {Бронь.Ваучер}', []) }}</td>
        </tr>
        <tr>
            <td>{{ __('Номер ваучера: :id', ['id' => $voucher->number]) }}</td>
        </tr>
        <tr>
            <td>{{ __('Дата создания: :createdAt', ['createdAt' => $voucher->createdAt]) }}</td>
        </tr>

        <br/>

        <tr>
            <td>{{ __('Информация о бронях:') }}</td>
        </tr>
        @foreach($services as $service)
            <tr>
                <td>{{ __('Номер брони: :id', ['id' => $service->id]) }}</td>
            </tr>
            <tr>
                <td>{{ __('Общая сумма: :amount', ['amount' => $service->price->total]) }}</td>
            </tr>
{{--            <tr>--}}
{{--                <td>{{ __('Статус брони: {Бронь.Статус}', []) }}</td>--}}
{{--            </tr>--}}
        @endforeach

        <tr>
            <td>{{ __('С уважением, :manager', ['manager' => $manager->name]) }}</td>
        </tr>
        <tr>
            <td>{Отправитель.Должность}</td>
        </tr>
        <tr>
            <td>{{ $manager->email }}</td>
        </tr>
        <tr>
            <td>{{ $manager->phone }}</td>
        </tr>
    </table>
@endsection
