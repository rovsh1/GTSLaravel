<p>
    Добрый день, {{$recipient->presentation}}!
    <br/>
    <br/>Просим Вас обработать запрос на бронирование: {{$booking->id}} &ndash; {{$booking->url}}
    <br/>
    <br/>Номер брони: {{$booking->id}}
    <br/>Дата и время создания: {{$booking->createdAt}}
    <br/>Дата заезда и выезда: {{$booking->dateCheckin}} - {{$booking->dateCheckout}}
    <br/>Количество ночей: {{$booking->nightsNumber}}
    <br/>Общая сумма: {{$booking->priceNet}}
    <br/>Статус брони: {{$booking->status}}
    <br/>Примечание: {{$booking->note}}
    <br/>
    <br/>

    @foreach($bookingRooms as $room)
        <br/>ID: {{$room->roomId}}
        <br/>Номер: {{$room->name}}
        <br/>Время заезда: {{$room->checkinTime}}
        <br/>Время выезда: {{$room->checkoutTime}}
        <br/>Ф.И. гостей: {{$room->guestsNames}}
        <br/>Цена за ночь: {{$room->priceNet}}
        <br/>Статус: {{$room->status}}
        <br/>Количество гостей: {{$room->guestsNumber}}
        <br/>Примечание: {{$room->note}}
        <br/>
    @endforeach

    <br/>
    <br/>Благодарим за сотрудничество,
    <br/>С уважением, {{$sender->presentation}}
    <br/>{{$sender->postName}}
    <br/>{{$sender->email}}
    <br/>{{$sender->phone}}
</p>