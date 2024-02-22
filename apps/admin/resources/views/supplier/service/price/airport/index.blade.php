@extends('supplier.service.price._partials.layout')

@section('scripts')
    {!! Js::variables([
        'supplierId' => $provider->id,
        'airports' => $airports,
        'services' => $services,
        'seasons' => $seasons,
        'currencies' => $currencies
    ]) !!}

    @vite('resources/views/supplier/service/price/airport/index.ts')
@endsection

@section('content-body')
    <div class="content-body">
        <div id="airport-prices"></div>
    </div>
@endsection
