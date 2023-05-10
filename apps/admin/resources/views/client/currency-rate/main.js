import '~resources/views/main'

$(() => {
  $('#form_data_currency_id').childCombo({
    url: '/client/currencies',
    value: +get_url_parameter('client_id'),
    disabledText: 'Выберите клиента',
    parent: '#form_data_client_id',
    dataIndex: 'client_id',
  })
})
