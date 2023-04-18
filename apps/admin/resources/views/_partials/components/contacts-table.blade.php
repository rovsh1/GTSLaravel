@if ($contacts->isEmpty())
    <div class="grid-empty-text">Отсутствуют</div>
@else
    <table class="table table-striped table-contacts">
        <tbody>
        @foreach($contacts as $contact)
            <tr class="contact-{{ $contact->type->key() }}" data-id="{{ $contact->id }}"
                data-type="{{ $contact->type->key() }}">
                <td class="column-icon" title="{{ $contact->type->title() }}">
                    <x-icon :key="$contact->type->key()"/>
                </td>
                <td class="column-description" data-name="description">{{ $contact->description }}</td>
                <td class="column-value" data-name="value" data-value="{{ $contact->value }}">
                    {!! Format::contact($contact) !!}
                </td>
                <td class="column-status">{{ $contact->main ? 'Основной' : '' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif
