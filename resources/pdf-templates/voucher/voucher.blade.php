@extends('layout.layout')

@push('css')
    <style>
        .voucher-header a,
        .voucher-header b,
        .voucher-header p {
            font-family: Arial, Helvetica, sans-serif !important;
        }

        .voucher-content li,
        .voucher-content a,
        .voucher-content p,
        .voucher-content i,
        .voucher-content u,
        .voucher-content b,
        .voucher-content div,
        .voucher-footer p,
         {
            font-size: 13px;
            font-family: Arial, Helvetica, sans-serif !important;
        }
        .voucher-wrapper * {
            font-family: Arial, Helvetica, sans-serif;
        }

        .voucher-footer *,
        .voucher-content * {
            font-size: 8px !important;
        }

        .voucher-wrapper p {
            margin: 0;
            padding: 0;
        }

        .voucher-wrapper .clear-both {
            overflow: auto;
        }

        .voucher-wrapper .column {
            float: left;
        }

        .voucher-footer {
            margin-top: 30px;
        }

        .voucher-header {
            padding: 0 9px;
            margin-bottom: 20px;
        }

        .voucher-header .voucher-header__left {
            width: 40%;
            text-align: left;
        }

        .voucher-header .voucher-header__right {
            width: 60%;
            text-align: right;
        }

        .voucher-header .voucher-header__right .voucher-header-title {
            font-size: 32px;
            color: #000;
        }

        .voucher-header .voucher-header__right .voucher-header-description {
            font-size: 11px;
        }

        .voucher-header .voucher-header__left img {
            width: 215px;
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

        .service-details {
            padding-bottom: 0.75rem;
        }

        .service-details__left {
            width: 55%;
        }

        .service-details__right {
            width: 45%;
        }

        .service-title {
            page-break-after: avoid !important;
            orphans: 0;
        }
    </style>
@endpush

@section('content')
    <div class="voucher-wrapper">
        <div class="voucher-header clear-both">
            <div class="column voucher-header__left">
                <img src="var:logo" alt="logo">
            </div>
            <div class="column voucher-header__right">
                <p class="voucher-header-title"><b>{{ __('ВАУЧЕР №:number', ['number' => $voucher->number]) }}</b></p>
                <p class="voucher-header-description">{{ __('Дата создания: :date', ['date' => $voucher->createdAt]) }}</p>
                <br/>
                <p class="voucher-header-description"><b>{{ $company->name }}</b></p>
                <p class="voucher-header-description">{{ __('Тел: :phone', ['phone'=>$company->phone]) }}</p>
                <p class="voucher-header-description">
                    <a href="mailto:{{ $company->email }}">E-mail: {{ $company->email }}</a>
                </p>
                <p class="voucher-header-description">{{ $company->legalAddress }}</p>
            </div>
        </div>
        <div class="voucher-content">
            @foreach($services as $service)
                @include('voucher._partials.service')
            @endforeach
        </div>
        <div class="voucher-footer">
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
@endsection
