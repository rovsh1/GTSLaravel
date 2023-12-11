@extends('supplier.service.price._partials.layout')

@section('scripts')
    {!! Js::variables([
        'supplierId' => $provider->id,
        'services' => $services,
        'seasons' => $seasons,
        'currencies' => $currencies
    ], 'supplier') !!}

    @vite('resources/views/supplier/service/price/other/index.ts')
@endsection

@section('content-body')
    <div class="content-body">
        <div id="other-prices"></div>
    </div>
@endsection
