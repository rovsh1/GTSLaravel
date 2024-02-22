<tr>
    <td class="text-align-left" style="width: 250px">
        <img src="var:logo" alt="" width="250">
    </td>
    <td style="width: 650px; ">
        <table class="text-align-right">
            <tbody>
            <tr>
                <td>{{$company->name}}</td>
            </tr>
            <tr>
                <td>{{ __('Тел: :phone', ['phone'=>$company->phone]) }}</td>
            </tr>
            <tr>
                <td>E-mail: {{$company->email}}</td>
            </tr>
            <tr>
                <td>{{$company->legalAddress}}</td>
            </tr>
            </tbody>
        </table>
    </td>
</tr>
