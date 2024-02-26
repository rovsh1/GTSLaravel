@extends('default.grid.grid')

@section('styles')
    @vite('resources/views/supplier-payment/main/main.scss')
@endsection

@section('scripts')
    @vite('resources/views/supplier-payment/main/main.ts')

    <div id="order-payment-modal"></div>
@endsection
