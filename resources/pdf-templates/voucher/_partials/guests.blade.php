<div class="service-guests">
    <p>{{ __('Гости (:count)', ['count' => count($service->guests)]) }}:</p>
    <ol>
        @foreach($service->guests ?? [] as $guest)
            <li>{{ $guest->fullName }} ({{ $guest->countryName }})</li>
        @endforeach
    </ol>
</div>

