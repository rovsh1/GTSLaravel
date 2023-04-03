@extends('layouts/main')

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
        <x-ui.actions-menu :$editUrl :$deleteUrl/>
        <div class="flex-grow-1"></div>

        {!! Layout::actions() !!}
    </div>

    <div class="content-body">
        <div class="card card-form">
            <div class="card-body">
                {!! $params !!}
            </div>
        </div>

        <div class="mt-3">
            <x-ui.collapsable-block header="Контакты (contacts_count)">
                <table class="table">
                    <tbody>
                    <tr>
                        <td>Телефон</td>
                        <td>55555</td>
                        <td>основной</td>
                        <td>кнопки</td>
                    </tr>
                    <tr>
                        <td>Телефон</td>
                        <td>55555</td>
                        <td>основной</td>
                        <td>кнопки</td>
                    </tr>
                    <tr>
                        <td>Телефон</td>
                        <td>55555</td>
                        <td>основной</td>
                        <td>кнопки</td>
                    </tr>
                    <tr>
                        <td>Телефон</td>
                        <td>55555</td>
                        <td>основной</td>
                        <td>кнопки</td>
                    </tr>
                    <tr>
                        <td>Телефон</td>
                        <td>55555</td>
                        <td>основной</td>
                        <td>кнопки</td>
                    </tr>
                    </tbody>
                </table>
            </x-ui.collapsable-block>
        </div>

        <div class="mt-3">
            <x-ui.collapsable-block header="Дополнительные параметры">
                Дополнительные параметры
            </x-ui.collapsable-block>
        </div>

        <div class="mt-3">
            <x-ui.collapsable-block header="Сотрудники (count)">
                Дополнительные параметры
            </x-ui.collapsable-block>
        </div>

        <div class="mt-3">
            <x-ui.collapsable-block header="Примечание">
                Дополнительные параметры
            </x-ui.collapsable-block>
        </div>

        <div class="mt-3">
            <x-ui.collapsable-block header="Администраторы (count)">
                Дополнительные параметры
            </x-ui.collapsable-block>
        </div>

        <div class="mt-3">
            <x-ui.collapsable-block header="Объекты и достопримечательности (count)">
                Дополнительные параметры
            </x-ui.collapsable-block>
        </div>
    </div>
@endsection
