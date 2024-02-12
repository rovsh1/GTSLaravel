@extends('mail-layout')

@section('content')

<div class="mail-content">
  <div class="text-block">
    <p>{{ __('Уважаемый партнер,') }}</p>
  </div>
  <div class="text-block">
    <p>Просим Вас обработать <span class="cancelled">запрос на аннулирование бронирования (транспорт)</span> <a href="#" target="_blank">по ссылке</a></p>
  </div>
  <div class="table-block">
    <table class="with-divider">
      <tr>
        <td class="title-column">Номер брони</td>
        <td class="value-column"><b>1111</b></td>
      </tr>
      <tr class="disable-divider">
        <td class="title-column">Дата и время создания:</td>
        <td class="value-column"><b>12.02.2024 16:34</b></td>
      </tr>
    </table>
  </div>
  <div class="text-block">
    <p><b>{{ __('Информация о заказе:') }}</b></p>
  </div>
  <div class="table-block">
    <table class="with-divider">
      <tr>
        <td class="title-column">Услуга:</td>
        <td class="value-column"><b>Аренда авто с водителем</b></td>
      </tr>
      <tr class="disable-divider">
        <td class="title-column">Дата/период:</td>
        <td class="value-column"><b>15.02.2024</b></td>
      </tr>
    </table>
  </div>
  <div class="text-block">
    <p>{{ __('С детальной информацией Вы можете ознакомиться во вложенном файле.') }}</p>
    <p>{{ __('Просим произвести сверку всех деталей заказа с Вашей стороны и сообщить в случае необходимости внесения каких-либо изменений Вашему менеджеру по бронированию.') }}</p>
  </div>
</div>

@endsection
