import { z } from 'zod'

import { formatDate } from '~lib/date'
import { requestInitialData } from '~lib/initial-data'

import '~resources/views/main'

const { clients, createClientUrl } = requestInitialData('view-initial-data-hotel-booking', z.object({
  createClientUrl: z.string(),
  clients: z.array(z.object({
    id: z.number(),
    name: z.string(),
    is_legal: z.boolean().optional(),
  })),
}))

$(() => {
  const toggleLegalIdInput = (required: boolean = true): void => {
    const $legalIdInput = $('#form_data_legal_id')
    const $legalIdField = $('div.field-legal_id')

    if (required) {
      $legalIdField.show().toggleClass('field-required', true)
      $legalIdInput.attr('required', 'required')

      return
    }
    $legalIdField.hide().toggleClass('field-required', false)
    $legalIdInput.removeAttr('required')
  }

  const handleChangeClientId = (orderId?: number): void => {
    const $clientIdInput = $('#form_data_client_id')
    const clientId = $clientIdInput.val()
    const client = clients.find((cl) => cl.id === Number(clientId))

    if (!client) {
      $('#form_data_order_id').attr('disabled', 'disabled')
    } else {
      $('#form_data_order_id').removeAttr('disabled')
    }

    toggleLegalIdInput(orderId === undefined ? Boolean(client?.is_legal) : false)

    const $legalIdInput = $('#form_data_legal_id')
    if ($legalIdInput.is('input')) {
      $legalIdInput.childCombo({
        url: '/client/legals/search',
        disabledText: 'Выберите клиента',
        parent: $clientIdInput,
        dataIndex: 'client_id',
      })
    }
  }

  const toggleOrderFields = (event: any): void => {
    const orderId: string = $(event.target).val() as string
    // const $currencyField = $('div.field-currency_id')
    // const $currencyInput = $('#form_data_currency_id')
    if (orderId.length === 0) {
      // $currencyField.show().toggleClass('field-required', true)
      // $currencyInput.attr('required', 'required')
      handleChangeClientId(undefined)
      return
    }

    handleChangeClientId(Number(orderId))
    // $currencyField.hide().toggleClass('field-required', false)
    // $currencyInput.removeAttr('required')
  }

  $('#form_data_client_id').select2()
  $('#form_data_city_id').select2()
  $('#form_data_manager_id').select2()

  $('#form_data_hotel_id').childCombo({
    url: '/hotels/search',
    disabledText: 'Выберите город',
    parent: $('#form_data_city_id'),
    dataIndex: 'city_id',
    useSelect2: true,
  })

  const $clientIdSelect = $('#form_data_client_id')
  const $clientIdSelectWrapper = $clientIdSelect.parent()
  $clientIdSelectWrapper.removeClass('col-sm-7').addClass('col-sm-6')

  const $createClientButton = $('<button />', {
    type: 'button',
    class: 'btn btn-add',
    html: '<i class="icon">add</i>Создать',
  }).click((): void => {
    window.WindowDialog({
      url: createClientUrl,
      title: 'Создать клиента',
      buttons: ['submit', 'cancel'],
    })
  })

  $clientIdSelectWrapper.after($('<div />', { class: 'col-sm-1' }).append($createClientButton))

  $clientIdSelect
    .change(() => handleChangeClientId(undefined))
    .ready(() => handleChangeClientId(undefined))

  $('#form_data_order_id').childCombo({
    url: '/booking-order/search',
    disabledText: 'Выберите клиента',
    parent: $clientIdSelect,
    dataIndex: 'client_id',
    allowEmpty: true,
    emptyText: false,
    emptyItem: 'Создать новый заказ',
    useSelect2: true,
    labelGetter: (order: Record<string, any>) => `№${order.id} от ${formatDate(order.createdAt)}`,
    childChange: toggleOrderFields,
  })
})
