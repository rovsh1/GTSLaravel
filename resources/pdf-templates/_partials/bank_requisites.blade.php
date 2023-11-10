@props(['currency' => \Module\Shared\Enum\CurrencyEnum::UZS])

<tr>
    <td colspan="4">
        <table>
            <tbody>
            <tr>
                <td colspan="2" width="717">
                    <p>
                        <strong>
                            В деталях платежа, просим указывать: "За ТУРИСТИЧЕСКИЕ УСЛУГИ согласно Дог-ру №__ от
                            __.__.202_г.":
                        </strong>
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="2" width="717">
                    <p>&nbsp;</p>
                </td>
            </tr>
            <tr>
                <td width="158">
                    <p><em>Бенефициар:</em></p>
                </td>
                <td width="559">
                    <p><strong>ООО «GOTOSTANS»</strong></p>
                </td>
            </tr>
            <tr>
                <td width="158">
                    <p><em>Адрес:</em></p>
                </td>
                <td width="559">
                    <p><strong>д.104A, ул. Кичик Бешегоч, Ташкент, 100015, Узбекистан</strong></p>
                </td>
            </tr>
            <tr>
                <td width="158">
                    <p><em>Тел:</em></p>
                </td>
                <td width="559">
                    <p><strong>(998 78) 1209012</strong></p>
                </td>
            </tr>
            <tr>
                <td width="158">
                    <p><em>Email:</em></p>
                </td>
                <td width="559">
                    <p><strong>info@gotostans.com</strong></p>
                </td>
            </tr>
            <tr>
                <td width="158">
                    <p><em>ИНН:</em></p>
                </td>
                <td width="559">
                    <p><strong>305 768 069</strong></p>
                </td>
            </tr>
            <tr>
                <td width="158">
                    <p><em>ОКЭД:</em></p>
                </td>
                <td width="559">
                    <p><strong>79900</strong></p>
                </td>
            </tr>
            <tr>
                <td colspan="2" width="717">
                    <p>&nbsp;</p>
                </td>
            </tr>

            @if($currency === \Module\Shared\Enum\CurrencyEnum::UZS)
                <tr>
                    <td colspan="2" width="717">
                        <p><strong>для оплат в UZS (узбекский сум)</strong></p>
                    </td>
                </tr>
                <tr>
                    <td width="158">
                        <p><em>Банк:</em></p>
                    </td>
                    <td width="559">
                        <p><strong>ЧАКБ «ORIENT FINANCE BANK» Мирабадский ф-л</strong></p>
                    </td>
                </tr>
                <tr>
                    <td width="158">
                        <p><em>Адрес:</em></p>
                    </td>
                    <td width="559">
                        <p><strong>7А, ул. Якуб Колас, г. Ташкент, 100023, Узбекистан</strong></p>
                    </td>
                </tr>
                <tr>
                    <td width="158">
                        <p><em>МФО:</em></p>
                    </td>
                    <td width="559">
                        <p><strong>01167</strong></p>
                    </td>
                </tr>
                <tr>
                    <td width="158">
                        <p><em>Р/с в сум (UZS):</em></p>
                    </td>
                    <td width="559">
                        <p><strong>20208000400934341001</strong></p>
                    </td>
                </tr>
            @endif

            @if($currency === \Module\Shared\Enum\CurrencyEnum::USD)
                <tr>
                    <td colspan="2" width="717">
                        <p><strong>для оплат в USD (доллар США)</strong></p>
                    </td>
                </tr>
                <tr>
                    <td width="158">
                        <p><em>Банк:</em></p>
                    </td>
                    <td width="559">
                        <p><strong>ЧАКБ «ORIENT FINANCE BANK» Мирабадский ф-л</strong></p>
                    </td>
                </tr>
                <tr>
                    <td width="158">
                        <p><em>Адрес банка:</em></p>
                    </td>
                    <td width="559">
                        <p><strong>7А, ул. Якуб Колас, г. Ташкент, 100023, Узбекистан</strong></p>
                    </td>
                </tr>
                <tr>
                    <td width="158">
                        <p><em>МФО:</em></p>
                    </td>
                    <td width="559">
                        <p><strong>01167</strong></p>
                    </td>
                </tr>
                <tr>
                    <td width="158">
                        <p><em>Р/с в долларах США (USD):</em></p>
                    </td>
                    <td width="559">
                        <p><strong>20208840900934341002</strong></p>
                    </td>
                </tr>
                <tr>
                    <td width="158">
                        <p><em>SWIFT:</em></p>
                    </td>
                    <td width="559">
                        <p><strong>ORFBUZ22</strong></p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" width="717">
                        <p>&nbsp;</p>
                    </td>
                </tr>
                <tr>
                    <td width="158">
                        <p><em>Банк корреспондент:</em></p>
                    </td>
                    <td width="559">
                        <p><strong>АКБ "Азия-Инвест Банк"</strong></p>
                    </td>
                </tr>
                <tr>
                    <td width="158">
                        <p><em>БИК:</em></p>
                    </td>
                    <td width="559">
                        <p><strong>044525234</strong></p>
                    </td>
                </tr>
                <tr>
                    <td width="158">
                        <p><em>Телекс:</em></p>
                    </td>
                    <td width="559">
                        <p><strong>914624 ASINV RU</strong></p>
                    </td>
                </tr>
                <tr>
                    <td width="158">
                        <p><em>SWIFT:</em></p>
                    </td>
                    <td width="559">
                        <p><strong>ASIJRUMM</strong></p>
                    </td>
                </tr>
                <tr>
                    <td width="158">
                        <p><em>Кор.сч. в долл. США (USD):</em></p>
                    </td>
                    <td width="559">
                        <p><strong>30111840800000002535</strong></p>
                    </td>
                </tr>
            @endif

            <tr>
                <td colspan="2" width="717">
                    <p>&nbsp;</p>
                </td>
            </tr>
            </tbody>
        </table>
        <p>&nbsp;</p>
    </td>
</tr>
