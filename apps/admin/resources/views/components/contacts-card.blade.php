<div class="card card-grid card-contacts" id="card-contacts">
    <div class="card-header">
        <h5 class="mb-0">Контакты</h5>
        @if ($contactsEditable)
            <div class="spacer"></div>
            <a href="#" id="btn-add-contact" class="btn btn-add">
                <x-icon key="add"/>
                Добавить контакт</a>
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
                        @if ($contactsEditable)
                            <td class="column-actions">
                                <div class="grid-actions">
                                    <div class="icon">more_vert</div>
                                    <div class="dropdown">
                                        <button class="btn-delete" data-form-action="delete" data-url="{{ $contactsUrl . '/' . $contact->id }}">Удалить</button>
                                        <hr>
                                        <button class="btn-edit" data-url="{{ $contactsUrl . '/' . $contact->id . '/edit' }}">Изменить</button>
                                    </div>
                                </div>
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
