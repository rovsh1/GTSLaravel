<div class="card" id="card-contacts">
    <div class="card-header">Контакты
        <a href="#" id="btn-add-contact">Добавить контакт</a>
    </div>
    <div class="card-body">
        @if ($contacts->isEmpty())
            <i class="empty">Отсутствуют</i>
        @else
            <table class="table-tabularsection table-contacts">
                @foreach($contacts as $contact)
                    @php
                        //dd($contact->type);
                    @endphp
                    <tr class="{{ $cls ?? '' }}" data-id="{{ $contact->id }}" data-type="{{ $contact->type->key() }}">
                        <td class="icon" title="{{ $contact->type->title() }}">
                            <x-icon :key="$contact->type->key()"/>
                        </td>
                        <td class="description" data-name="description">{{ $contact->description }}</td>
                        <td class="value" data-name="value" data-value="{{ $contact->value }}">
                            {!! Format::contact($contact) !!}
                        </td>
                        <td class="status">{{ $contact->main ? 'Основной' : '' }}</td>
                        <td class="status">
                            <button class="btn-delete" data-form-action="delete" data-url="{{ $contactsUrl . '/' . $contact->id }}">Удалить</button>
                            <button class="btn-edit" data-url="{{ $contactsUrl . '/' . $contact->id . '/edit' }}">Изменить</button>
                        </td>
                    </tr>
                @endforeach
            </table>
        @endif
    </div>
</div>
