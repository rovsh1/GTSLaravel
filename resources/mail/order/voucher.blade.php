@extends('mail-layout')
@props(['services' => []])

@section('content')
    Уважаемый {Бронь.Клиент}, добрый день!

    Ваши брони подтверждены, отправляем Вам ваучер – {Бронь.Ваучер}
    Номер ваучера: {Бронь.Номер oбщего ваучера}
    Дата создания: {Бронь.Дата создания oбщего ваучера}

    Информация о бронях:
    @foreach($services as $service)
        Номер брони: {Бронь.Идентификатор}
        Дата заезда и выезда: {Бронь.Дата заезда} - {Бронь.Дата выезда}
        Количество ночей: {Бронь.Кол-во ночей}
        Город: {Бронь.Город}
        Отель: {Бронь.Отель}
        Общая сумма: {Бронь.Общая сумма брутто}
        Статус брони: {Бронь.Статус}
    @endforeach

    С уважением, {Транспортная бронь.Менеджер брони}
    {Отправитель.Должность}
    {Отправитель.Email}
    {Отправитель.Телефон}
@endsection
