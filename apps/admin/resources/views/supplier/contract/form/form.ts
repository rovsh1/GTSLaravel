import '~resources/views/main'

$(() => {
  const supplierId = $('#form_data_supplier_id').val()

  $('#form_data_service_ids').childCombo({
    urlGetter: (type: number) => {
      if (Number(type) === 1) {
        return '/hotels/search'
      }
      return `/supplier/${supplierId}/services/search`
    },
    disabledText: 'Выберите тип услуги',
    parent: $('#form_data_service_type'),
    dataIndex: 'type',
    useSelect2: true,
    // @todo сделать этот селект multiple (Важно, чтобы значение передавалось как массив)
  })
})
