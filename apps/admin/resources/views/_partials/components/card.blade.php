@props(['id', 'header', 'collapsable' => false])

<div {{ $attributes->merge(['class' => 'card', 'id' => $id]) }}>
    @if($collapsable)
        <div class="card-header collapsable collapsed" data-bs-toggle="collapse" data-bs-target="#collapse-{{$id}}"
             aria-expanded="false"
             aria-controls="collapse-{{$id}}">
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
    @else
        <div class="card-header">
            <h5 class="mb-0">
                {{$header}}
            </h5>
            @if (isset($headerControls))
                <div class="card-header-controls">
                    {{$headerControls}}
                </div>
            @endif
        </div>
    @endif

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
