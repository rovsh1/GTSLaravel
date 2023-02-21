@extends('layouts/default')

@section('title', 'Страны')

@section('content')
    <?php //$layout->menu('breadcrumbs') ?>

    <div class="card">
        <h5 class="card-header"><?= $layout->title ?></h5>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <?= $grid ?>
                <?= $paginator ?>
            </div>
        </div>
    </div>
@endsection
