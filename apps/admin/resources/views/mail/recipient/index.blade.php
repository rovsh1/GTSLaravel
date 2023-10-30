@extends('layouts.main')

@section('scripts')
    @vite('resources/views/mail/template/form.ts')
@endsection

@section('content')
    <x-ui.content-title/>

    <div class="content-body">
        <form method="POST"
        >
            <div class="card card-form">
                <div class="card-body">

                </div>
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </form>
    </div>
@endsection
