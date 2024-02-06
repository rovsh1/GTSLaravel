@extends('layout.layout')

@push('css')
    <style>
        .invoice-header a,
        .invoice-header b,
        .invoice-header p {
            font-family: Arial, Helvetica, sans-serif !important;
        }

        .invoice-content li,
        .invoice-content a,
        .invoice-content p,
        .invoice-content i,
        .invoice-content u,
        .invoice-content b,
        .invoice-content div,
        .invoice-footer p,
        {
            font-size: 13px;
            font-family: Arial, Helvetica, sans-serif !important;
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

        .invoice-wrapper .w-15 {
            width: 15%;
        }

        .invoice-wrapper .w-17 {
            width: 17.5%;
        }

        .invoice-wrapper .w-25 {
            width: 25%;
        }

        .invoice-wrapper .w-28 {
            width: 28%;
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

        .invoice-wrapper .w-72 {
            width: 72%;
        }

        .invoice-wrapper .w-75 {
            width: 75%;
        }

        .invoice-wrapper .w-85 {
            width: 85%;
        }

        .invoice-wrapper .text-right {
            text-align: right;
        }

        .invoice-wrapper .text-center {
            text-align: center;
        }

        .invoice-footer .mark {
            width: 225px;
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
            font-size: 32px !important;
        }

        .invoice-header .invoice-header__right .invoice-header-description,
        .invoice-header .invoice-header__right .invoice-header-description a {
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

        .total-amount p b {
            font-size: 19px !important;
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
            <div class="client-info">
                <div class="clear-both">
                    <div class="column w-15">
                        <p>{{ __('Клиент') }}:</p>
                    </div>
                    <div class="column w-85">
                        <p><b>{{ $client->name }}</b></p>
                    </div>
                </div>
                <div class="clear-both">
                    <div class="column w-15">
                        <p>{{ __('Договор') }}:</p>
                    </div>
                    <div class="column w-85">
                        <p>{{ $client->contractNumber }}</p>
                    </div>
                </div>
                <div class="clear-both">
                    <div class="column w-15">
                        <p>{{ __('Адрес') }}:</p>
                    </div>
                    <div class="column w-85">
                        <p>{{ $client->address }}</p>
                    </div>
                </div>
                <div class="clear-both">
                    <div class="column w-15">
                        <p>{{ __('Телефон') }}:</p>
                    </div>
                    <div class="column w-85">
                        <p>{{ $client->phone }}</p>
                    </div>
                </div>
                <div class="clear-both">
                    <div class="column w-15">
                        <p>Email:</p>
                    </div>
                    <div class="column w-85">
                        <p><a href="mailto:{{ $client->email }}">{{ $client->email }}</a></p>
                    </div>
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
                    @php
                        $amount = $invoice->totalPenalty ?? $invoice->totalAmount;
                    @endphp
                    <p><b>{{ Format::number($amount) }} {{ $order->currency }}</b></p>
                </div>
            </div>
            <div class="note">
                <i><u>{{ __('Важное примечание:') }}</u> {{ __("Оплата должна быть произведена в полной мере, без учета комиссии межбанковских переводов, налогов и сборов.\nВ случае необходимости уплаты таковых, все расчеты должны быть произведены заказчиком сверх сумм, указанных в настоящем инвойсе.") }}
                </i>
            </div>
            <div class="additional-info clear-both">
                <div class="clear-both">
                    <div class="column w-28">
                        <p>{{ __('Бенефициар') }}:</p>
                    </div>
                    <div class="column w-72">
                        <p><b>ООО «GOTOSTANS»</b></p>
                    </div>
                </div>
                <div class="clear-both">
                    <div class="column w-28">
                        <p>{{ __('Адрес') }}:</p>
                    </div>
                    <div class="column w-72">
                        <p>д.104A, ул. Кичик Бешагоч, Ташкент, 100015, Узбекистан</p>
                    </div>
                </div>
                <div class="clear-both">
                    <div class="column w-28">
                        <p>{{ __('Тел') }}:</p>
                    </div>
                    <div class="column w-72">
                        <p>+998 78 1209012</p>
                    </div>
                </div>
                <div class="clear-both">
                    <div class="column w-28">
                        <p>{{ __('ИНН') }}:</p>
                    </div>
                    <div class="column w-72">
                        <p>305768069</p>
                    </div>
                </div>
                <div class="clear-both">
                    <div class="column w-28">
                        <p>{{ __('ОКЭД') }}:</p>
                    </div>
                    <div class="column w-72">
                        <p>79900</p>
                    </div>
                </div>
                <br/>
                <div class="clear-both">
                    <div class="column w-28">
                        <p>{{ __('Банк') }}:</p>
                    </div>
                    <div class="column w-72">
                        <p>ЧАКБ «ORIENT FINANCE BANK» Мирабадский филиал</p>
                    </div>
                </div>
                <div class="clear-both">
                    <div class="column w-28">
                        <p>{{ __('Адрес') }}:</p>
                    </div>
                    <div class="column w-72">
                        <p>7А, ул. Якуб Колас, г. Ташкент, 100023, Узбекистан</p>
                    </div>
                </div>
                <div class="clear-both">
                    <div class="column w-28">
                        <p>{{ __('МФО') }}:</p>
                    </div>
                    <div class="column w-72">
                        <p>01071</p>
                    </div>
                </div>
                <div class="clear-both">
                    <div class="column w-28">
                        <p>{{ __('Код ЦБУ') }}:</p>
                    </div>
                    <div class="column w-72">
                        <p>11795</p>
                    </div>
                </div>
                <br/>
                <div class="clear-both">
                    <div class="column w-28">
                        <p>{{ __('Р/с в :currency', ['currency' => \Sdk\Shared\Enum\CurrencyEnum::UZS->name]) }}:</p>
                    </div>
                    <div class="column w-72">
                        <p>20208000300934341001</p>
                    </div>
                </div>
                <div class="clear-both">
                    <div class="column w-28">
                        <p>{{__('Р/с в :currency', ['currency' => \Sdk\Shared\Enum\CurrencyEnum::USD->name]) }}:</p>
                    </div>
                    <div class="column w-72">
                        <p>20208840800934341002</p>
                    </div>
                </div>
                <div class="clear-both">
                    <div class="column w-28">
                        <p>{{ __('Р/с в :currency', ['currency' => \Sdk\Shared\Enum\CurrencyEnum::EUR->name]) }}:</p>
                    </div>
                    <div class="column w-72">
                        <p>20208978100934341002</p>
                    </div>
                </div>
                <div class="clear-both">
                    <div class="column w-28">
                        <p>{{ __('Р/с в :currency', ['currency' => \Sdk\Shared\Enum\CurrencyEnum::RUB->name]) }}:</p>
                    </div>
                    <div class="column w-72">
                        <p>20208643900934341002</p>
                    </div>
                </div>
                <div class="clear-both">
                    <div class="column w-28">
                        <p>SWIFT:</p>
                    </div>
                    <div class="column w-72">
                        <p>ORFBUZ22</p>
                    </div>
                </div>
                <br/>
                <div class="clear-both">
                    <div class="column w-28">
                        <p>{{ __('Банк корреспондент') }}:</p>
                    </div>
                    <div class="column w-72">
                        <p>АКБ "Азия-Инвест Банк"</p>
                    </div>
                </div>
                <div class="clear-both">
                    <div class="column w-28">
                        <p>{{ __('БИК') }}:</p>
                    </div>
                    <div class="column w-72">
                        <p>044525234</p>
                    </div>
                </div>
                <div class="clear-both">
                    <div class="column w-28">
                        <p>{{ __('Телекс') }}:</p>
                    </div>
                    <div class="column w-72">
                        <p>914624 ASINV RU</p>
                    </div>
                </div>
                <div class="clear-both">
                    <div class="column w-28">
                        <p>SWIFT:</p>
                    </div>
                    <div class="column w-72">
                        <p>ASIJRUMM</p>
                    </div>
                </div>
                <div class="clear-both">
                    <div class="column w-28">
                        <p>{{ __('Кор.сч. в :currency', ['currency' => \Sdk\Shared\Enum\CurrencyEnum::USD->name]) }}
                            :</p>
                    </div>
                    <div class="column w-72">
                        <p>30111840800000002535</p>
                    </div>
                </div>
                <div class="clear-both">
                    <div class="column w-28">
                        <p>{{ __('Кор.сч. в :currency', ['currency' => \Sdk\Shared\Enum\CurrencyEnum::EUR->name]) }}
                            :</p>
                    </div>
                    <div class="column w-72">
                        <p>30111978400000002535</p>
                    </div>
                </div>
                <div class="clear-both">
                    <div class="column w-28">
                        <p>{{ __('Кор.сч. в :currency', ['currency' => \Sdk\Shared\Enum\CurrencyEnum::RUB->name]) }}
                            :</p>
                    </div>
                    <div class="column w-72">
                        <p>30111810500000002535</p>
                    </div>
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
                    <img style="margin-top: -50px" class="mark" src="var:stamp" alt="mark">
                </div>
            </div>
            <div style="margin-top: -10px;">
                <div class="clear-both">
                    <div class="column w-25">
                        <p>{{ __('Менеджер') }}:</p>
                    </div>
                    <div class="column w-75">
                        <p><b>{{ $manager->fullName }}</b></p>
                    </div>
                </div>
                <div class="clear-both">
                    <div class="column w-25">
                        <p>E-mail:</p>
                    </div>
                    <div class="column w-75">
                        <p>{{ $manager->email }}</p>
                    </div>
                </div>
                <div class="clear-both">
                    <div class="column w-25">
                        <p>{{ __('Мобильный номер') }}:</p>
                    </div>
                    <div class="column w-75">
                        <p>{{ $manager->phone }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
