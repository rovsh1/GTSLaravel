<div class="card card-grid card-contacts" id="card-contacts" data-route="{{ $contactsEditable ? $contactsUrl : '' }}">
    <div class="card-header">
        <h5 class="mb-0">Контакты</h5>
        @if ($contactsEditable)
            <div class="spacer"></div>
            <button class="btn btn-add">
                <x-icon key="add"/>
                Добавить контакт
            </button>
        @endif
    </div>
    <div class="card-body">
        @if ($contacts->isEmpty())
            <i class="empty">Отсутствуют</i>
        @else
            <table class="table table-striped table-contacts">
                <tbody>
                @foreach($contacts as $contact)
                    <tr class="contact-{{ $contact->type->key() }}" data-id="{{ $contact->id }}" data-type="{{ $contact->type->key() }}">
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
    </div>
</div>
