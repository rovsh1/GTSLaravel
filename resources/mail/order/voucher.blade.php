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
        <td class="title-column">{{ __('Номер ваучера:') }}</td>
        <td class="value-column"><b>{{ $voucher->number }}</b></td>
      </tr>
      <tr class="disable-divider">
        <td class="title-column">{{ __('Дата и время создания:') }}</td>
        <td class="value-column"><b>{{ $voucher->createdAt }}</b></td>
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
          {{ __('Список гостей:') }}
        </td>
        <td class="value-column">
          <table class="with-divider">
            @foreach($order->guests as $index => $guest)
              <tr class="{{ $index === count($order->guests) - 1 ? 'disable-divider' : '' }}">
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
        <td class="title-column">{{ __('Дата начала поездки:') }}</td>
        <td class="value-column"><b>{{ $order->period->dateFrom }}</b></td>
      </tr>
      <tr class="disable-divider">
        <td class="title-column">{{ __('Дата завершения поездки:') }}</td>
        <td class="value-column"><b>{{ $order->period->dateTo }}</b></td>
      </tr>
    </table>
  </div>
  <div class="text-block">
    <p>{{ __('С детальной информацией Вы можете ознакомиться во вложенном файле.') }}</p>
    <p>{{ __('Просим произвести сверку всех деталей заказа с Вашей стороны и сообщить в случае необходимости внесения каких-либо изменений Вашему менеджеру по бронированию.') }}</p>
  </div>
</div>

@endsection
