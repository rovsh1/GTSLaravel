@extends('service-provider.service.price._partials.layout')

@section('scripts')
    @vite('resources/views/service-provider/service/price/transfer/index.ts')
@endsection

@section('head-end')
    <script>
      window['view-initial-data-service-provider'] = {{ Js::from([
            'providerId' => $provider->id,
            'services' => $services,
            'cars' => $cars,
            'seasons' => $seasons,
        ]) }}
    </script>
@endsection

@section('content-body')
    <div class="content-body">
        <div id="transfer-prices"></div>
    </div>
@endsection
