@props(['id', 'header', 'collapsable' => false])
@php
    $headerAttributes = $collapsable
        ? 'class="card-header collapsable collapsed"'
        : 'class="card-header"';
    $toggleAttributes = $collapsable
        ? ('data-bs-toggle="collapse"'
            . ' data-bs-target="#collapse-' . $id . '"'
            . ' aria-expanded="false"'
            . ' aria-controls="collapse-' . $id . '"')
        : 'class="card-header"';
@endphp

<div {{ $attributes->merge(['class' => 'card', 'id' => $id]) }}>
    <div {!! $headerAttributes !!}>
        <div class="card-header-toggle" {!! $toggleAttributes !!}></div>
        <h5 class="mb-0">
            {{$header}}
        </h5>
        @if (isset($headerControls))
            <div class="spacer"></div>
            <div class="card-header-controls">
                {{$headerControls}}
            </div>
        @endif
    </div>

    @if($collapsable)
        <div class="collapse" id="collapse-{{$id}}">
            <div class="card-body">
                {{$slot}}
            </div>
        </div>
    @else
        <div class="card-body">
            {{$slot}}
        </div>
    @endif
</div>