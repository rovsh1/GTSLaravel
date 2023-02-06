@extends('layouts/default')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="javascript:void(0);">Справочники</a>
            </li>
            <li class="breadcrumb-item active">Страны</li>
        </ol>
    </nav>

    <div class="card">
        <h5 class="card-header">Страны</h5>
        <div class="table-responsive text-nowrap">
            <?= $grid ?>
        </div>
    </div>
@endsection
