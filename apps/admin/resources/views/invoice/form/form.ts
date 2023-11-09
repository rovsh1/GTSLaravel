import { formatDate } from '~lib/date'

import '~resources/views/main'

$(() => {
  const $clientIdField = $('#form_data_client_id').select2()

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
