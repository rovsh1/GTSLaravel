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

const { bookingID } = requestInitialData('view-initial-data-service-booking', z.object({
  bookingID: z.number().nullable(),
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

  const $serviceTypeField = $('#form_data_service_type')
  const $supplierField = $('#form_data_supplier_id').childCombo({
    url: '/supplier/search',
    disabledText: 'Выберите тип услуги',
    parent: $serviceTypeField,
    dataIndex: 'service_type',
    useSelect2: true,
    allowEmpty: true,
    emptyText: false,
  })

  $('#form_data_service_id').childCombo({
    urlGetter: (supplierId: number) => `/supplier/${supplierId}/services/search?type=${$serviceTypeField.val()}`,
    disabledText: 'Выберите поставщика',
    parent: $supplierField,
    useSelect2: true,
    data: {
      only_with_contract: 1,
    },
  })

  const $clientIdSelect = $('#form_data_client_id').select2()
    .change(() => handleChangeClientId(undefined))
    .ready(() => handleChangeClientId(undefined))

  const reloadClientsSelect = async (): Promise<void> => {
    const clientsList = await axios.get('/client/list')
    const clientsListData = clientsList && clientsList.data ? clientsList.data : []
    clients = clientsListData
    const clientsSelectOptions: Select2Option[] = mapClientsToSelect2Options(clientsListData)
    const selectedClientId = $clientIdSelect.val()
    $clientIdSelect.select2('destroy').empty().select2({ data: clientsSelectOptions }).val(selectedClientId || '')
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
    eventBus.on('client-created', async (event: { clientId: number }) => {
      await reloadClientsSelect()
      $clientIdSelect.val(event.clientId).trigger('change')
      handleChangeClientId(event.clientId)
    })
  }

  reloadClientsSelect()
})
