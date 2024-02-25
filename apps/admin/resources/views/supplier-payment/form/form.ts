import '~resources/views/main'

$(() => {
  const $supplierIdSelect = $('#form_data_supplier_id')

  $('#form_data_payment_currency').childCombo({
    urlGetter: (supplierId: number) => `/supplier/${supplierId}/currencies`,
    disabledText: 'Выберите поставщика',
    parent: $supplierIdSelect,
    dataIndex: 'supplier_id',
  })
})
