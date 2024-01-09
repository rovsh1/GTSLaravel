@extends('layout.layout')

@push('css')
    <style>
        .invoice-wrapper * {
            font-family: Arial, Helvetica, sans-serif;
        }

        .invoice-footer *,
        .invoice-content * {
            font-size: 13px;
        }

        .invoice-wrapper p {
            margin: 0;
            padding: 0;
        }

        .invoice-wrapper .clear-both {
            overflow: auto;
        }

        .invoice-wrapper .column {
            float: left;
        }

        .invoice-wrapper .w-10 {
            width: 10%;
        }

        .invoice-wrapper .w-17 {
            width: 17.5%;
        }

        .invoice-wrapper .w-45 {
            width: 45%;
        }

        .invoice-wrapper .w-55 {
            width: 55%;
        }

        .invoice-wrapper .w-50 {
            width: 50%;
        }

        .invoice-wrapper .text-right {
            text-align: right;
        }

        .invoice-wrapper .text-center {
            text-align: center;
        }

        .invoice-footer .mark {
            width: 225px;
            margin-top: -50px;
        }

        .invoice-footer .manager {
            margin-top: -40px;
        }

        .invoice-header {
            padding: 0 9px;
            margin-bottom: 20px;
        }

        .invoice-header .invoice-header__left {
            width: 40%;
            text-align: left;
        }

        .invoice-header .invoice-header__right {
            width: 60%;
            text-align: right;
        }

        .invoice-header .invoice-header__right .invoice-header-title {
            font-size: 32px;
        }

        .invoice-header .invoice-header__right .invoice-header-description {
            font-size: 11px;
        }

        .invoice-header .invoice-header__left img {
            width: 215px;
        }

        .client-info {
            padding: 9px 2px;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            margin-bottom: 10px;
        }

        .total-amount {
            padding: 0px 9px;
            margin-bottom: 15px;
        }

        .total-amount * {
            font-size: 19px;
        }

        .note {
            margin-bottom: 15px;
        }

        .service-item ol {
            margin: 0;
        }

        .service-item {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 0 9px;
            margin-bottom: 9px;
        }

        .service-item ol li {
            padding-left: 10px;
        }

        .service-title {
            page-break-after: avoid !important;
            orphans: 0;
        }
    </style>
@endpush

@section('content')
    <div class="invoice-wrapper">
        <div class="invoice-header clear-both">
            <div class="column invoice-header__left">
                <img src="var:logo" alt="logo">
            </div>
            <div class="column invoice-header__right">
                <p class="invoice-header-title"><b>{{ __('ИНВОЙС №:number', ['number' => $order->number]) }}</b></p>
                <p class="invoice-header-description">{{ __('Дата создания: :date', ['date' => $invoice->createdAt]) }}</p>
                <br/>
                <p class="invoice-header-description"><b>{{ $company->name }}</b></p>
                <p class="invoice-header-description">{{ __('Тел: :phone', ['phone'=>$company->phone]) }}</p>
                <p class="invoice-header-description">
                    <a href="mailto:{{ $company->email }}">E-mail: {{ $company->email }}</a>
                </p>
                <p class="invoice-header-description">{{ $company->legalAddress }}</p>
            </div>
        </div>
        <div class="invoice-content">
            <div class="client-info clear-both">
                <div class="column" style="width: 15%;">
                    <p>{{ __('Клиент') }}:</p>
                    <p>{{ __('Договор') }}:</p>
                    <p>{{ __('Адрес') }}:</p>
                    <p>{{ __('Телефон') }}:</p>
                    <p>Email:</p>
                </div>
                <div class="column" style="width: 85%;">
                    <p><b>{{ $client->name }}</b></p>
                    <p>{{ $client->contractNumber }}</p>
                    <p>{{ $client->address }}</p>
                    <p>{{ $client->phone }}</p>
                    <p><a href="mailto:{{ $client->email }}">{{ $client->email }}</a></p>
                </div>
            </div>

            @foreach($services as $service)
                @include('invoice._partials.service')
            @endforeach

            <div class="total-amount clear-both">
                <div class="column w-50">
                    <p><b>{{ __('ИТОГО К ОПЛАТЕ') }}</b></p>
                </div>
                <div class="column w-50 text-right">
                    <p><b>{{ Format::number($invoice->totalAmount) }} {{ $order->currency }}</b></p>
                </div>
            </div>
            <div class="note">
                <i><u>{{ __('Важное примечание:') }}</u> {{ __("Оплата должна быть произведена в полной мере, без учета комиссии межбанковских переводов, налогов и сборов.\nВ случае необходимости уплаты таковых, все расчеты должны быть произведены заказчиком сверх сумм, указанных в настоящем инвойсе.") }}
                </i>
            </div>
            <div class="additional-info clear-both">
                <div class="column" style="width: 28%;">
                    <p>{{ __('Бенефициар') }}:</p>
                    <p>{{ __('Адрес') }}:</p>
                    <p>{{ __('Тел') }}:</p>
                    <p>{{ __('ИНН') }}:</p>
                    <p>{{ __('ОКЭД') }}:</p>
                    <br/>
                    <p>{{ __('Банк') }}:</p>
                    <p>{{ __('Адрес') }}:</p>
                    <p>{{ __('МФО') }}:</p>
                    <p>{{ __('Код ЦБУ') }}:</p>
                    <br/>
                    <p>{{ __('Р/с в :currency', ['currency' => \Sdk\Shared\Enum\CurrencyEnum::UZS->name]) }}:</p>
                    <p>{{__('Р/с в :currency', ['currency' => \Sdk\Shared\Enum\CurrencyEnum::USD->name]) }}:</p>
                    <p>{{ __('Р/с в :currency', ['currency' => \Sdk\Shared\Enum\CurrencyEnum::EUR->name]) }}:</p>
                    <p>{{ __('Р/с в :currency', ['currency' => \Sdk\Shared\Enum\CurrencyEnum::RUB->name]) }}:</p>
                    <p>SWIFT:</p>
                    <br/>
                    <p>{{ __('Банк корреспондент') }}:</p>
                    <p>{{ __('БИК') }}:</p>
                    <p>{{ __('Телекс') }}: </p>
                    <p>SWIFT:</p>
                    <p>{{ __('Кор.сч. в :currency', ['currency' => \Sdk\Shared\Enum\CurrencyEnum::USD->name]) }}:</p>
                    <p>{{ __('Кор.сч. в :currency', ['currency' => \Sdk\Shared\Enum\CurrencyEnum::EUR->name]) }}:</p>
                    <p>{{ __('Кор.сч. в :currency', ['currency' => \Sdk\Shared\Enum\CurrencyEnum::RUB->name]) }}:</p>
                </div>
                <div class="column" style="width: 72%;">
                    <p><b>ООО «GOTOSTANS»</b></p>
                    <p>д.104A, ул. Кичик Бешагоч, Ташкент, 100015, Узбекистан</p>
                    <p>+998 78 1209012</p>
                    <p>305768069</p>
                    <p>79900</p>
                    <br/>
                    <p>ЧАКБ «ORIENT FINANCE BANK» Мирабадский филиал </p>
                    <p>7А, ул. Якуб Колас, г. Ташкент, 100023, Узбекистан </p>
                    <p>01071</p>
                    <p>11795</p>
                    <br/>
                    <p>20208000300934341001</p>
                    <p>20208840800934341002</p>
                    <p>20208978100934341002</p>
                    <p>20208643900934341002</p>
                    <p>ORFBUZ22</p>
                    <br/>
                    <p>АКБ "Азия-Инвест Банк"</p>
                    <p>044525234</p>
                    <p>914624 ASINV RU</p>
                    <p>ASIJRUMM</p>
                    <p>30111840800000002535</p>
                    <p>30111978400000002535</p>
                    <p>30111810500000002535</p>
                </div>
            </div>
        </div>
        <div class="invoice-footer">
            <div class="clear-both">
                <br/>
                <br/>
                <br/>
                <div class="column w-50">
                    <p>{{ __('Спасибо за сотрудничество.') }}</p>
                    <br/>
                    <br/>
                    <p>{{ $company->name }}</p>
                    <p>{{ __('Директор: :signer', ['signer' => $company->signer]) }}</p>
                </div>
                <div class="column w-50 text-center">
                    <img class="mark" src="var:stamp" alt="mark">
                </div>
            </div>
            <div class="manager clear-both">
                <div class="column" style="width: 25%;">
                    <p>{{ __('Менеджер') }}:</p>
                    <p>E-mail:</p>
                    <p>{{ __('Мобильный номер') }}:</p>
                </div>
                <div class="column" style="width: 75%;">
                    <p><b>{{ $manager->fullName }}</b></p>
                    <p>{{ $manager->email }}</p>
                    <p>{{ $manager->phone }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
