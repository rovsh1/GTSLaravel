import { isEmpty } from 'lodash'
import { createPinia } from 'pinia'
import { z } from 'zod'

import axios from '~resources/js/app/api'
import CreateClientButton from '~resources/views/booking/shared/CreateClientButton.vue'
import { mapClientsToSelect2Options, Select2Option } from '~resources/views/booking/shared/lib/constants'

import { Client } from '~api/client'

import { formatDate } from '~lib/date'
import { useApplicationEventBus } from '~lib/event-bus'
import { requestInitialData } from '~lib/initial-data'
import { createVueInstance } from '~lib/vue'

import '~resources/views/main'

const { orderId } = requestInitialData('view-initial-data-booking-order', z.object({
  orderId: z.number().nullable(),
}))

let clients = [] as Client[]

const pinia = createPinia()

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

  const handleChangeClientId = (): void => {
    const $clientIdInput = $('#form_data_client_id')
    const clientId = $clientIdInput.val()
    const client = clients.find((cl) => cl.id === Number(clientId))

    toggleCurrencyIdField(!client?.currency)
    toggleLegalIdInput(Boolean(client?.is_legal))

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

  const toggleOrderFields = (): void => {
    const isOrderIdEmpty = isEmpty(orderId)
    toggleCurrencyIdField(isOrderIdEmpty)
    if (isOrderIdEmpty) {
      handleChangeClientId()
      return
    }

    handleChangeClientId()
  }

  $('#form_data_manager_id').select2()

  const $clientIdSelect = $('#form_data_client_id').select2()
    .change(() => handleChangeClientId())
    .ready(() => handleChangeClientId())

  const reloadClientsSelect = async (): Promise<void> => {
    const clientsList = await axios.get('/client/list')
    const clientsListData = clientsList && clientsList.data ? clientsList.data : []
    clients = clientsListData
    const clientsSelectOptions: Select2Option[] = mapClientsToSelect2Options(clientsListData)
    $clientIdSelect.select2('destroy').empty().select2({ data: clientsSelectOptions }).val('')
      .trigger('change')
  }

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

  if (orderId === null) {
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
    eventBus.on('client-created', async (event: { clientId: number }) => {
      await reloadClientsSelect()
      $clientIdSelect.val(event.clientId).trigger('change')
      // @todo доработать проброс клиента
      handleChangeClientId()
    })
  }

  reloadClientsSelect()
})
