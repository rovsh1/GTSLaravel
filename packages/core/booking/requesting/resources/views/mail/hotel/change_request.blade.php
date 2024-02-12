@extends('mail-layout')

@section('content')

<div class="mail-content">
  <div class="text-block">
    <p>{{ __('Уважаемый партнер,') }}</p>
  </div>
  <div class="text-block">
    <p>Просим Вас обработать <span class="changed">запрос на изменение бронирования</span> <a href="#" target="_blank">по ссылке</a></p>
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
    <table>
      <tr>
        <td class="title-column">
          Список номеров:
        </td>
        <td class="value-column">
          <table class="with-divider">
            <tr>
              <td><b>1. Стандартный двухместный</b></td>
            </tr>
            <tr class="disable-divider">
              <td><b>2. Стандартный одноместный</b></td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </div>
  <div class="table-block">
    <table class="with-divider">
      <tr>
        <td class="title-column">Дата заезда:</td>
        <td class="value-column"><b>12.02.2024</b></td>
      </tr>
      <tr class="disable-divider">
        <td class="title-column">Дата выезда:</td>
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
