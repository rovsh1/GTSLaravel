@extends('supplier.service.price._partials.layout')

@section('scripts')
    @vite('resources/views/supplier/service/price/other/index.ts')
@endsection

@section('head-end')
    <script>
      window['view-initial-data-supplier'] = {{ Js::from([
            'supplierId' => $provider->id,
            'services' => $services,
            'seasons' => $seasons,
            'currencies' => $currencies
        ]) }}
    </script>
@endsection

@section('content-body')
    <div class="content-body">
        <div id="other-prices"></div>
    </div>
@endsection
