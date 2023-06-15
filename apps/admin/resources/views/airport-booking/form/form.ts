import '~resources/views/main'

$(() => {
  $('#form_data_client_id').childCombo({
    url: '/client/search',
    value: window.get_url_parameter('order_id'),
    disabledText: 'Выберите заказ',
    parent: $('#form_data_order_id'),
    dataIndex: 'order_id',
    allowEmpty: true,
  })

  $('#form_data_service_id').childCombo({
    url: '/service-provider/services/search',
    value: window.get_url_parameter('city_id'),
    disabledText: 'Выберите город',
    parent: $('#form_data_city_id'),
    dataIndex: 'city_id',
  })

  $('#form_data_airport_id').childCombo({
    url: '/airports/search',
    value: window.get_url_parameter('service_id'),
    disabledText: 'Выберите услугу',
    parent: $('select[name="data[service_id]"]'),
    dataIndex: 'service_id',
  })
})
