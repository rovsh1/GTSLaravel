@extends('layouts.main')

@section('styles')
    @vite('resources/views/default/form/form.scss')
@endsection

@section('scripts')
    @vite('resources/views/supplier/service/form/form.ts')
@endsection

@section('head-end')
    <script>
      window['view-initial-data-supplier-service'] = {{ Js::from([
            'supplier' => $supplier,
            'airports' => $airports,
            'cities' => $cities,
            'cancelUrl' => $cancelUrl,
            'createUrl' => $createUrl,
            'service' => $service,
        ]) }}
    </script>
@endsection

@section('content')
    <x-ui.content-title/>

    <div class="content-body">
        <div class="card card-form">
            <div class="card-body">
                <div id="service-form"></div>
            </div>
        </div>
    </div>
@endsection

