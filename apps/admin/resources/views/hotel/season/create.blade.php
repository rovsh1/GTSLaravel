@extends('default.form.form')

@section('head-end')
    <script id="hotel-season-edit-initial-data">
        window['view-initial-data'] = {!!
            json_encode([
                'hotelID' => $hotel->id,
            ])
        !!}
    </script>
@endsection

@section('scripts')
    @vite('resources/views/hotel/season/edit.ts')
@endsection
