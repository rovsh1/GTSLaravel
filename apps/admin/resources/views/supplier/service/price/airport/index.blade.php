@extends('supplier.service.price._partials.layout')

@section('scripts')
    @vite('resources/views/supplier/service/price/airport/index.ts')
@endsection

@section('head-end')
    <script>
      window['view-initial-data-supplier'] = {{ Js::from([
            'supplierId' => $provider->id,
            'airports' => $airports,
            'services' => $services,
            'seasons' => $seasons,
            'currencies' => $currencies
        ]) }}
    </script>
@endsection

@section('content-body')
    <div class="content-body">
        <div id="airport-prices"></div>
    </div>
@endsection
