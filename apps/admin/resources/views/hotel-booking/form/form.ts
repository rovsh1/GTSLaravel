import { Client } from '~api/client'

import '~resources/views/main'

$(() => {
  $('#form_data_hotel_id').childCombo({
    url: '/hotels/search',
    value: window.get_url_parameter('city_id'),
    disabledText: 'Выберите город',
    parent: $('#form_data_city_id'),
    dataIndex: 'city_id',
  })

  let clients: Client[] = []

  const handleChangeClientId = (event: any): void => {
    const clientId = $(event.target).val()
    const client = clients.find((cl) => cl.id === Number(clientId))

    const $legalIdInput = $('#form_data_legal_id')
    const $legalIdField = $('div.field-legal_id')
    if (!client?.is_legal) {
      $legalIdField.hide().toggleClass('field-required', false)
      $legalIdInput.removeAttr('required')
      return
    }
    $legalIdField.show().toggleClass('field-required', true)
    $legalIdInput.attr('required', 'required')

    if ($legalIdInput.is('input')) {
      $legalIdInput.childCombo({
        url: '/client/legals/search',
        value: window.get_url_parameter('client_id'),
        disabledText: 'Выберите клиента',
        parent: $('#form_data_client_id'),
        dataIndex: 'client_id',
      })
    }
  }

  $('#form_data_client_id').childCombo({
    url: '/client/search',
    value: window.get_url_parameter('order_id'),
    disabledText: 'Выберите заказ',
    parent: $('#form_data_order_id'),
    dataIndex: 'order_id',
    allowEmpty: true,
    load: (items: Client[]): void => {
      clients = items
    },
    childChange: handleChangeClientId,
  })
})
