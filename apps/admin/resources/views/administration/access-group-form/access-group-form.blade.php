@extends('layouts.main')

@section('styles')
    @vite('resources/views/administration/access-group-form/access-group-form.scss')
@endsection

@section('scripts')
    @vite('resources/views/administration/access-group-form/access-group-form.ts')
@endsection

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>

        {!! Layout::actions() !!}
    </div>

    <div class="content-body">
        <div class="card card-form">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <x-ui.tab target="main" active="true">Основные</x-ui.tab>
                    <x-ui.tab target="rules">Правила</x-ui.tab>
                </ul>
            </div>
            <div class="card-body">
                <form
                    class="tab-content"
                    action="{{ $form->action }}"
                    method="{{ strtoupper($form->method) === 'GET' ? 'GET' : 'POST' }}"
                    enctype="multipart/form-data">
                    <div id="main" role="tabpanel" class="tab-pane fade show active" aria-labelledby="main-tab">
                        <div class="form-group">{!! $form !!}</div>
                    </div>

                    <div id="rules" role="tabpanel" class="tab-pane fade" aria-labelledby="rules-tab">
                        <div class="d-flex align-items-start">
                            <div class="nav flex-column nav-pills me-3" id="permissions-tabs" aria-orientation="vertical">
                                @foreach($categories as $category)
                                    <button class="nav-link{{ $category->key === $default ? ' active' : '' }}"
                                            type="button"
                                            role="tab"
                                            data-category="{{ $category->key }}"
                                            aria-selected="{{ $category->key === $default ? 'true' : 'false' }}">{{ $category->title }}</button>
                                @endforeach
                            </div>
                            @php
                                $icons = [
                                    'read'=> 'visibility',
                                    'create'=> 'add',
                                    'update'=> 'edit',
                                    'delete'=> 'delete',
                                    'auth as'=> 'person'
                                ];
                                $renderItem = function($item) use ($icons, $permissions) {
                                    $rules = $permissions[$item->key];
                                    $html = '<div class="permissions">';
                                    foreach ($rules->available as $r) {
                                        $allowed = in_array($r, $rules->allowed);
                                        $html .= '<div data-permission="' . $r . '"'
                                            .' class="item' . ($allowed ? ' allowed' : '') . '">'
                                            . '<i class="icon">' . $icons[$r] . '</i>'
                                            . '<input type="hidden" name="data[rules][' . $rules->id . '][' . $r . ']" value="' . ($allowed ? '1' : '0') . '"/>'
                                            .'</div>';
                                    }
                                    $html .= '</div>';
                                    return '<div class="menu-item">'
                                        .$html
                                        . $item->text
                                        . '</div>';
                                };
                            @endphp
                            <div class="permissions-control-wrapper" id="permissions">
                                @foreach($categories as $category)
                                    <div class="permissions-category-menu" style="{{ $category->key === $default ? '' : 'display:none' }}" data-category="{{ $category->key }}">
                                        @if(count($category->items()) > 0)
                                            <nav>
                                                @foreach($category->items() as $item)
                                                    {!! $renderItem($item) !!}
                                                @endforeach
                                            </nav>
                                        @endif

                                        @foreach($category->groups() as $group)
                                            <div class="group">
                                                <div class="group-title">{{ $group->title }}</div>
                                                <div class="group-items">
                                                    <nav>
                                                        @foreach($group->items() as $item)
                                                            {!! $renderItem($item) !!}
                                                        @endforeach
                                                    </nav>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="form-buttons">
                        <button type="submit" class="btn btn-primary">{{ $submitText ?? 'Сохранить' }}</button>
                        @if(isset($cancelUrl))
                            <a href="{{ $cancelUrl }}" class="btn btn-cancel">Отмена</a>
                        @endif
                        <div class="spacer"></div>
                        <x-form.delete-button :url="$deleteUrl ?? null"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
