@extends('supplier.service.price._partials.layout')

@section('scripts')
    {!! Js::variables([
        'supplierId' => $provider->id,
        'services' => $services,
        'cars' => $cars,
        'seasons' => $seasons,
        'currencies' => $currencies
    ], 'supplier') !!}

    @vite('resources/views/supplier/service/price/transfer/index.ts')
@endsection

@section('content-body')
    <div class="content-body">
        <div id="transfer-prices"></div>
    </div>
@endsection
