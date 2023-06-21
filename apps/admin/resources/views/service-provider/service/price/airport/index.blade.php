@extends('service-provider.service.price._partials.layout')

@section('scripts')
    @vite('resources/views/service-provider/service/price/airport/index.ts')
@endsection

@section('head-end')
    <script>
      window['view-initial-data-service-provider'] = {{ Js::from([
            'providerId' => $provider->id,
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
