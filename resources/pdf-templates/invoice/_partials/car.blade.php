<tr>
    <td class="text-align-center" style="padding: 5px 0;">'. ++$this->servicesCount .'</td>
    <td style="padding: 5px 0;">Модель авто: '.$entity->car_name.'</td>
    <td class="text-align-center last" style="padding: 5px 0;">'.$car['count'].'</td>
    <td class="text-align-center last" style="padding: 5px 0;">
        '.\format\price($carsPrice[$entity->car_name]['brutto']).'
    </td>
    <td class="text-align-center" style="padding: 5px 0;">
        '.\format\price($carsPrice[$entity->car_name]['total_gross']).'
    </td>
</tr>
