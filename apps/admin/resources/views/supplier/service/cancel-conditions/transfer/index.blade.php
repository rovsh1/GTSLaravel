@extends('supplier.service.cancel-conditions.layout.layout')

@section('scripts')
    @vite('resources/views/supplier/service/cancel-conditions/transfer/index.ts')
@endsection

@section('head-end')
    <script>
      window['view-initial-data-supplier'] = {{ Js::from([
            'supplierId' => $provider->id,
            'services' => $services,
            'cars' => $cars,
            'seasons' => $seasons,
        ]) }}
    </script>
@endsection

@section('content-body')
    <div class="content-body">
        <div id="transfer-cancel-conditions"></div>
    </div>
@endsection
