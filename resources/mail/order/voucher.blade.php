@extends('mail-layout')

@section('content')

<div class="mail-content">
  <div class="text-block">
    <p>{{ __('Уважаемый партнер,') }}</p>
  </div>
  <div class="text-block">
    <p>{{ __('Ваш заказ подтвержден, Ваучер доступен') }} <a href="{{ $voucher->fileUrl }}" target="_blank">по ссылке</a></p>
  </div>
  <div class="table-block">
    <table class="with-divider">
      <tr>
        <td>{{ __('Номер ваучера:') }}:</td>
        <td><b>{{ $voucher->number }}</b></td>
      </tr>
      <tr>
        <td>{{ __('Дата и время создания:') }}</td>
        <td><b>$voucher->createdAt</b></td>
      </tr>
    </table>
  </div>
  <div class="text-block">
    <p><b>{{ __('Информация о заказе:') }}</b></p>
  </div>
  <div class="table-block">
    <table>
      <tr>
        <td>
          {{ __('Список гостей:') }}
        </td>
        <td>
          <table class="with-divider">
            @foreach($order->guests as $index => $guest)
              <tr>
                <td><b>{{++$index}}. {{ $guest->fullName }}</b></td>
              </tr>
            @endforeach
          </table>
        </td>
      </tr>
    </table>
  </div>
  <div class="table-block">
    <table class="with-divider">
      <tr>
        <td>{{ __('Дата начала поездки:') }}</td>
        <td><b>{{ $order->period->dateFrom }}</b></td>
      </tr>
      <tr>
        <td>{{ __('Дата завершения поездки:') }}</td>
        <td><b>{{ $order->period->dateTo }}</b></td>
      </tr>
    </table>
  </div>
  <div class="text-block">
    <p>{{ __('С детальной информацией Вы можете ознакомиться во вложенном файле.') }}</p>
    <p>{{ __('Просим произвести сверку всех деталей заказа с Вашей стороны и сообщить в случае необходимости внесения каких-либо изменений Вашему менеджеру по бронированию.') }}</p>
  </div>
</div>

@endsection
