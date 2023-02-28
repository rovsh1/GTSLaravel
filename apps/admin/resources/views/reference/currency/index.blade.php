@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="javascript:void(0);">Справочники</a>
            </li>
            <li class="breadcrumb-item active">{{ $layout->page->H1 }}</li>
        </ol>
    </nav>

    <div class="card">
        <h5 class="card-header">
            {{ $layout->page->H1 }}
            <a href="{{ route('currency.create') }}"><button type="button" class="btn btn-primary float-end">Добавить</button></a>
        </h5>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                {!! $grid  !!}
            </div>
        </div>
    </div>
@endsection
