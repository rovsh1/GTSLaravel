import { isEmpty } from 'lodash'
import { createPinia } from 'pinia'
import { z } from 'zod'

import CreateClientButton from '~resources/views/booking/CreateClientButton.vue'

import { formatDate } from '~lib/date'
import { useApplicationEventBus } from '~lib/event-bus'
import { requestInitialData } from '~lib/initial-data'
import { createVueInstance } from '~lib/vue'

import '~resources/views/main'

const { bookingID } = requestInitialData('view-initial-data-service-booking', z.object({
  bookingID: z.number().nullable(),
}))

const pinia = createPinia()

const clients: any[] = [
  { id: 14, name: 'test', currency: 'UZS' },
]

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

  const toggleCurrencyIdField = (state?: boolean) => {
    const $currencyField = $('div.field-currency')
    const $currencyInput = $('#form_data_currency')
    if (state) {
      $currencyField.show().toggleClass('field-required', true)
      $currencyInput.attr('required', 'required')
      return
    }
    $currencyField.hide().toggleClass('field-required', false)
    $currencyInput.removeAttr('required')
  }

  const handleChangeClientId = (orderId?: number): void => {
    const $clientIdInput = $('#form_data_client_id')
    const clientId = $clientIdInput.val()
    const client = clients.find((cl) => cl.id === Number(clientId))

    if (bookingID === null) {
      if (!client) {
        $('#form_data_order_id').attr('disabled', 'disabled')
      } else {
        $('#form_data_order_id').removeAttr('disabled')
        toggleCurrencyIdField(!client.currency)
      }
    }

    toggleLegalIdInput(orderId === undefined ? Boolean(client?.is_legal) : false)

    const $legalIdInput = $('#form_data_legal_id')
    if ($legalIdInput.is('input')) {
      // еще не был инстанцирован, т.е. выведен как hidden input в html через php form
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

    const isOrderIdEmpty = isEmpty(orderId)
    toggleCurrencyIdField(isOrderIdEmpty)
    if (isOrderIdEmpty) {
      handleChangeClientId(undefined)
      return
    }

    handleChangeClientId(Number(orderId))
  }

  $('#form_data_manager_id').select2()

  $('#form_data_service_id').childCombo({
    urlGetter: (type: number) => `/supplier/services/${type}/list`,
    disabledText: 'Выберите тип услуги',
    parent: $('#form_data_service_type'),
    dataIndex: 'type',
    useSelect2: true,
  })

  const $clientIdSelect = $('#form_data_client_id').select2()
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

  if (bookingID === null) {
    const $clientIdSelectWrapper = $clientIdSelect.parent()
    $clientIdSelectWrapper.removeClass('col-sm-7')
    $clientIdSelectWrapper.addClass('col-sm-6')

    const $createClientButton = $('<div />', { id: 'create-client-button' })

    $clientIdSelectWrapper.after($('<div />', { class: 'col-sm-1' })
      .append($createClientButton))

    createVueInstance({
      rootComponent: CreateClientButton,
      rootContainer: '#create-client-button',
      plugins: [pinia],
    })

    const eventBus = useApplicationEventBus()
    eventBus.on('client-created', (event: { clientId: number }) => {
      $clientIdSelect.val(event.clientId).trigger('change')
    })
  }
})
