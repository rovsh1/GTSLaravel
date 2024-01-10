@extends('mail-layout')

@section('content')
    <table>
        <tr>
            <td>{{ __('Уважаемый :client, добрый день!', ['client' => $client->name]) }}</td>
        </tr>

        <tr>
            <td>
                {{ __('Ваши брони подтверждены, отправляем Вам инвойс') }} - <a href="{{ $invoice->fileUrl }}" target="_blank">по ссылке</a>
            </td>
        </tr>
        <tr>
            <td>{{ __('Номер инвойса: :id', ['id' => $invoice->number]) }}</td>
        </tr>
        <tr>
            <td>{{ __('Дата создания: :createdAt', ['createdAt' => $invoice->createdAt]) }}</td>
        </tr>

        <br/>

        <tr>
            <td>{{ __('Информация о бронях:') }}</td>
        </tr>
        @foreach($services as $service)
            <tr>
                <td>{{ $service->title }}</td>
            </tr>
            {{--            <tr>--}}
            {{--                <td>{{ __('Общая сумма: :amount :currency', ['amount' => $service->price->total, 'currency' => $service->price->currency]) }}</td>--}}
            {{--            </tr>--}}
            {{--            <tr>--}}
            {{--                <td>{{ __('Статус брони: {Бронь.Статус}', []) }}</td>--}}
            {{--            </tr>--}}
        @endforeach

        <tr>
            <td>{{ __('С детальной информацией об услугах можете ознакомиться во вложенном файле.') }}</td>
        </tr>

        <tr>
            <td>{{ __('С уважением, :manager', ['manager' => $manager->fullName]) }}</td>
        </tr>
        <tr>
            <td>{{ $manager->post }}</td>
        </tr>
        <tr>
            <td>{{ $manager->email }}</td>
        </tr>
        <tr>
            <td>{{ $manager->phone }}</td>
        </tr>
    </table>
@endsection
