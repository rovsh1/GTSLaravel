@extends('supplier.service.cancel-conditions.layout.layout')

@section('scripts')
    @vite('resources/views/supplier/service/cancel-conditions/service/index.ts')
@endsection

@section('head-end')
    <script>
      window['view-initial-data-supplier'] = {{ Js::from([
            'supplierId' => $provider->id,
            'services' => $services,
            'seasons' => $seasons,
        ]) }}
    </script>
@endsection

@section('content-body')
    <div class="content-body">
        <div id="service-cancel-conditions"></div>
    </div>
@endsection
