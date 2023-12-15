<table>
    <tbody>
    <tr>
        <td>&ensp;</td>
    </tr>
    <tr>
        <td>
            {{ __('Менеджер ') }}: {{ $manager->fullName }}
        </td>
    </tr>
    <tr>
        <td>E-mail: {{ $manager->email }}</td>
    </tr>
    <tr>
        <td>{{ __('Мобильный номер: :phone', ['phone' => $manager->phone]) }}</td>
    </tr>
    </tbody>
</table>
