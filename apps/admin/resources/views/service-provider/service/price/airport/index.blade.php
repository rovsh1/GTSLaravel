@extends('service-provider.service.price._partials.layout')

@section('scripts')
    @vite('resources/views/service-provider/service/price/airport/index.ts')
@endsection

@section('content-body')
    <div class="content-body">
        <div class="card card-form">
            <div class="card-header">
                фывфыв
            </div>

            <div class="card-body">
                <div class="tab-content">
                    <div id="main" role="tabpanel" class="tab-pane fade show active" aria-labelledby="main-tab">
                        <div id="prices-table"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
