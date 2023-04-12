<form method="post" action="{{$servicesUrl}}" enctype="multipart/form-data" id="hotel-services">
    <input type="hidden" name="_method" value="put">
    <table class="table table-striped">
        @foreach($services as $index => $service)
            <tr>
                <td>
                    <input
                        type="checkbox"
                        @checked($hotelServices->where('service_id', $service->id)->first())
                        name="data[services][{{$index}}][service_id]"
                        id="hsf_{{$index}}_cb"
                        value="{{$service->id}}"
                    >
                    <label for="hsf_{{$index}}_cb">{{$service->name}}</label>
                </td>
                <td>
                    <input
                        type="checkbox"
                        @checked($hotelServices->where('service_id', $service->id)->first()?->is_paid)
                        name="data[services][{{$index}}][is_paid]"
                        id="hsf_{{$index}}_paid_cb"
                        value="{{$service->id}}"
                    >
                    <label for="hsf_{{$index}}_paid_cb">Платный</label>
                </td>
            </tr>
        @endforeach
    </table>
</form>
