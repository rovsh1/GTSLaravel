import '~resources/views/main'

$(() => {
  const $clientIdSelect = $('#form_data_client_id')

  $('#form_data_payment_currency').childCombo({
    urlGetter: (clientId: number) => `/client/${clientId}/currencies`,
    disabledText: 'Выберите клиента',
    parent: $clientIdSelect,
    dataIndex: 'client_id',
  })
})
