@extends('layouts/main')

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>

        {!! Layout::actions() !!}
    </div>

    <div class="content-body">
        <div class="card card-form">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <x-tab target="main" active="true">Основные</x-tab>
                    <x-tab target="rules">Правила</x-tab>
                </ul>
            </div>
            <div class="tab-content card-body">
                <form action="{{ $form->action }}" method="{{ strtoupper($form->method) === 'GET' ? 'GET' : 'POST' }}" enctype="multipart/form-data">
                    <div id="main" role="tabpanel" class="tab-pane fade show active" aria-labelledby="main-tab">
                        <div class="form-group">{!! $form !!}</div>
                    </div>
                    <div id="rules" role="tabpanel" class="tab-pane fade" aria-labelledby="rules-tab">
                        <div id="acl-rules">
                            @foreach($categories as $a) @endforeach

                            @foreach($prototypes as $prototype)
                                <div class="item">
                                    <div class="name">{{ $prototype->title() }}</div>
                                    <div class="permissions">
                                        @foreach($prototype->permissions() as $permission)
                                            <div class="permission">
                                                {{ $permission }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-buttons">
                        <button type="submit" class="btn btn-primary">{{ $submitText ?? 'Сохранить' }}</button>
                        @if(isset($cancelUrl))
                            <a href="{{ $cancelUrl }}" class="btn btn-cancel">Отмена</a>
                        @endif
                        <div class="spacer"></div>
                        @if(isset($deleteUrl) && $deleteUrl)
                            <a href="#" data-url="{{ $deleteUrl }}" class="btn btn-delete">
                                <x-icon key="delete"/>
                                Удалить</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
