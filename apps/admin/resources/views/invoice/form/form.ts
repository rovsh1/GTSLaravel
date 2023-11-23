import { formatDate } from '~lib/date'
import { useSelectElement } from '~lib/select-element/select-element'

import '~resources/views/main'

$(async () => {
  const $clientIdField = (await useSelectElement(document.querySelector<HTMLSelectElement>('#form_data_client_id')))?.select2Instance

  $('#form_data_order_ids').childCombo({
    url: '/booking-order/search',
    disabledText: 'Выберите клиента',
    parent: $clientIdField,
    dataIndex: 'client_id',
    useSelect2: true,
    labelGetter: (order: Record<string, any>) => `№${order.id} от ${formatDate(order.createdAt)}`,
    data: {
      only_waiting_invoice: 1,
    },
  })
})
