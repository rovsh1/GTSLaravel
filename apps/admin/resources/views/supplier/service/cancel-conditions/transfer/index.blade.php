@extends('supplier.service.cancel-conditions.layout.layout')

@section('scripts')
    {!! Js::variables([
        'supplierId' => $provider->id,
        'services' => $services,
        'cars' => $cars,
        'seasons' => $seasons,
    ]) !!}

    @vite('resources/views/supplier/service/cancel-conditions/transfer/index.ts')
@endsection

@section('content-body')
    <div class="content-body">
        <div id="transfer-cancel-conditions"></div>
    </div>
@endsection
