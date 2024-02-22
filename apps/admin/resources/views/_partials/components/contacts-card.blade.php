@props(['collapsable' => false, 'contactsEditable' => false])

<x-ui.card id="card-contacts" data-route="{{ $contactsEditable ? $contactsUrl : '' }}" header="Контакты ({{$contacts->count()}})" collapsable="{{$collapsable}}" class="card-grid card-contacts">
    @if ($contactsEditable)
        <x-slot:header-controls>
            <button class="btn btn-add">
                <x-icon key="add"/>
                Добавить контакт
            </button>
        </x-slot:header-controls>
    @endif

    @include('_partials/components/contacts-table')
</x-ui.card>
