<div class="accordion" id="{{$id}}">
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapse-{{$id}}" aria-expanded="false" aria-controls="collapse-{{$id}}">
                {{$header}}
            </button>
        </h2>
        <div id="collapse-{{$id}}" class="accordion-collapse collapse" data-bs-parent="#{{$id}}">
            <div class="accordion-body">
                {{$slot}}
            </div>
        </div>
    </div>
</div>
