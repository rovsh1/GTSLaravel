@extends('default.grid.grid')

@section('styles')
    @vite('resources/views/default/grid/grid.scss')
@endsection

@section('scripts')
    @vite('resources/views/payment/main/main.ts')

    <div id="order-payment-modal"></div>
@endsection
