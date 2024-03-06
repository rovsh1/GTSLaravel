<input type="hidden" name="_hotel-rooms" value="{{ json_encode($rooms) }}">
<input type="hidden" name="_hotel-usabilities" value="{{ json_encode($hotelUsabilities) }}">
<form method="post" action="{{$usabilitiesUrl}}" enctype="multipart/form-data">
    <input type="hidden" name="_method" value="put">
    <div class="accordion" id="hotel-usabilities">
        @foreach($usabilities->groupBy('group_id') as $groupId => $groupUsabilities)
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse-{{$groupId}}"
                            aria-controls="collapse-{{$groupId}}">
                        {{$groupUsabilities->first()->group_name}}
                    </button>
                </h2>
                <div id="collapse-{{$groupId}}" class="accordion-collapse collapse"
                     data-bs-parent="#hotel-usabilities">
                    <div class="accordion-body">
                        <div class="hotel-usabilities accordion-list unselectable">
                            <section class="expanded">
                                <ul class="section-body">
                                    @foreach($groupUsabilities as $usability)
                                        <li data-id="{{$usability->id}}">
                                            <input
                                                type="checkbox"
                                                @checked($hotelUsabilities->where('usability_id', $usability->id)->first())
                                                name="data[usabilities][{{$usability->id}}][all]"
                                                id="f_hu_{{$usability->id}}_a"
                                                value="1"
                                            >
                                            <label for="f_hu_{{$usability->id}}_a">{{$usability->name}}</label>
                                        </li>
                                    @endforeach
                                </ul>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</form>

