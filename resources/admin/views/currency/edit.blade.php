@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="javascript:void(0);">Справочники</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('currency.index') }}">Валюты</a>
            </li>
            <li class="breadcrumb-item active">{{ $layout->page->H1 }}</li>
        </ol>
    </nav>

    <div class="card">
        <h5 class="card-header">
            {{ $layout->page->H1 }}
            <a href="{{ route('currency.index') }}"><button type="button" class="btn btn-primary float-end">Назад</button></a>
        </h5>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                Тут будет редактирование валюты с ID = {{ $currency->id }}
            </div>
        </div>
    </div>
@endsection
