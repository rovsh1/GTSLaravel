@extends('BookingRequesting::pdf-templates.layout.layout')

@section('header')
    <div class="column document-header__left">
        <img src="var:logo" alt="logo">
    </div>
    <div class="column document-header__right">
        <p class="document-header-title"><b>ИЗМЕНЕНИЕ БРОНИРОВАНИЯ</br>(ТРАНСПОРТ)</b></p>
        <p class="document-header-title"><b>#{{ $booking->number }}</b></p>
        <p class="document-header-description">Дата создания: {{ $booking->createdAt }}</p>
        <br/>
        <p class="document-header-description"><b>{{ $company->name }}</b></p>
        <p class="document-header-description">{{ $company->phone }}</p>
        <p class="document-header-description"><a href="mailto:{{ $company->email }}">{{ $company->email }}</a></p>
        <p class="document-header-description">{{ $company->legalAddress }}</p>
    </div>
@endsection

@section('content')
    <div class="document-content-header text-center">
        <p><b class="changed">ПРОСИМ ВНЕСТИ ИЗМЕНЕНИЯ В БРОНИРОВАНИЕ</b></p>
    </div>
    <div class="client-info ">
        <p>Клиент:<b> <span>{{ $client->name }}</span></b></p>
    </div>

    @include('BookingRequesting::pdf-templates.transfer._partials.cars')

    <div class="total-amount clear-both">
        <div class="column w-50">
            <p><b>ИТОГО К ОПЛАТЕ</b></p>
        </div>
        <div class="column w-50 text-right">
            <p><b>{{ Format::number($booking->supplierPrice->amount) }} {{ Format::number($booking->supplierPrice->currency) }}</b></p>
        </div>
    </div>
@endsection

@section('footer')
    <div class="clear-both">
        <br/>
        <div class="column w-72">
            <p>Спасибо за сотрудничество.</p>
            <br/>
            <div class="manager clear-both">
                <div class="column w-28">
                    <p>Менеджер:</p>
                    <p>Email:</p>
                    <p>Мобильный номер:</p>
                </div>
                <div class="column w-72">
                    <p><b>{{ $manager->fullName }}</b></p>
                    <p>{{ $manager->email }}</p>
                    <p>{{ $manager->phone }}</p>
                </div>
            </div>
        </div>
        <div class="column w-28 text-right">
            <img class="mark" src="var:stamp" alt="mark">
        </div>
    </div>
@endsection
