import { isEmpty } from 'lodash'
import { createPinia } from 'pinia'
import { z } from 'zod'

import axios from '~resources/js/app/api'
import CreateClientButton from '~resources/views/booking/shared/CreateClientButton.vue'
import { mapClientsToSelect2Options, Select2Option } from '~resources/views/booking/shared/lib/constants'
import { createVueInstance } from '~resources/vue/vue'

import { Client } from '~api/client'

import { useSelectElement } from '~widgets/select-element/select-element'

import { formatDate } from '~lib/date'
import { useApplicationEventBus } from '~lib/event-bus'
import { requestInitialData } from '~lib/initial-data'

import '~resources/views/main'

const { bookingID } = requestInitialData(z.object({
  bookingID: z.number().nullable(),
}))

let clients = [] as Client[]

const pinia = createPinia()

$(async () => {
  const urlParams = new URLSearchParams(window.location.search)
  const clientIdParam = urlParams.get('client_id')
  const orderIdParam = urlParams.get('order_id')

  let $orderIdSelect: JQuery<HTMLElement> | null = null

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
        toggleCurrencyIdField((client.currency === null || client.currency === undefined))
      }
    } else {
      toggleCurrencyIdField((!client?.currency === null || client?.currency === undefined))
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

  let $clientIdSelect = (await useSelectElement(document.querySelector<HTMLSelectElement>('#form_data_client_id')))?.select2Instance

  await useSelectElement(document.querySelector<HTMLSelectElement>('#form_data_city_id'))
  await useSelectElement(document.querySelector<HTMLSelectElement>('#form_data_manager_id'))

  $('#form_data_hotel_id').childCombo({
    url: '/hotels/search',
    disabledText: 'Выберите город',
    parent: $('#form_data_city_id'),
    dataIndex: 'city_id',
  })

  const reloadClientsSelect = async (): Promise<void> => {
    const clientsList = await axios.get('/client/list')
    const clientsListData = clientsList && clientsList.data ? clientsList.data : []
    clients = clientsListData
    const clientsSelectOptions: Select2Option[] = mapClientsToSelect2Options(clientsListData)
    const selectedClientId = $clientIdSelect?.val()
    $clientIdSelect = (await useSelectElement(document.querySelector<HTMLSelectElement>('#form_data_client_id'), {
      data: clientsSelectOptions,
    }))?.select2Instance
    if (bookingID === null && clientIdParam && orderIdParam) {
      $clientIdSelect?.val(clientIdParam).trigger('change')
    } else {
      $clientIdSelect?.val(selectedClientId || '').trigger('change')
    }
  }

  if (bookingID === null) {
    const $clientIdSelectWrapper = $clientIdSelect?.parent()
    $clientIdSelectWrapper?.removeClass('col-sm-7').addClass('col-sm-5')

    const $createClientButton = $('<div />', { id: 'create-client-button' })

    $clientIdSelectWrapper?.after($('<div />', { class: 'col-sm-2' })
      .append($createClientButton))

    createVueInstance({
      rootComponent: CreateClientButton,
      rootContainer: '#create-client-button',
      plugins: [pinia],
    })

    const eventBus = useApplicationEventBus()
    eventBus.on('client-created', async (event: { clientId: number }) => {
      await reloadClientsSelect()
      $clientIdSelect?.val(event.clientId).trigger('change')
      handleChangeClientId(event.clientId)
    })
  }

  $clientIdSelect?.change(() => handleChangeClientId(undefined))
    .ready(() => handleChangeClientId(undefined))

  $orderIdSelect = await (async () => $('#form_data_order_id').childCombo({
    url: '/booking-order/search',
    disabledText: 'Выберите клиента',
    parent: $clientIdSelect,
    dataIndex: 'client_id',
    allowEmpty: true,
    emptyItem: 'Создать новый заказ',
    labelGetter: (order: Record<string, any>) => `№${order.id} от ${formatDate(order.createdAt)}`,
    childChange: toggleOrderFields,
    load: () => {
      if (bookingID === null && clientIdParam && orderIdParam) {
        $orderIdSelect?.val(orderIdParam).trigger('change')
      } else if (bookingID === null) {
        $orderIdSelect?.val('').trigger('change')
      }
    },
  }))()

  reloadClientsSelect()
})
