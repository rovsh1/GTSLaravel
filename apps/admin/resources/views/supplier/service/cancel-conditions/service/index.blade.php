@extends('supplier.service.cancel-conditions.layout.layout')

@section('scripts')
    {!! Js::variables([
        'supplierId' => $provider->id,
        'services' => $services,
        'seasons' => $seasons,
    ]) !!}

    @vite('resources/views/supplier/service/cancel-conditions/service/index.ts')
@endsection

@section('content-body')
    <div class="content-body">
        <div id="service-cancel-conditions"></div>
    </div>
@endsection
