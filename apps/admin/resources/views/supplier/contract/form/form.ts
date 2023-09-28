import '~resources/views/main'

$(() => {
  const supplierId = $('#form_data_supplier_id').val()

  $('#form_data_service_id').childCombo({
    urlGetter: (type: number) => {
      if (Number(type) === 1) {
        return '/hotels/search'
      }
      if (Number(type) === 2) {
        return `/supplier/${supplierId}/services-airport/list`
      }
      return `/supplier/${supplierId}/services-transfer/list`
    },
    disabledText: 'Выберите тип услуги',
    parent: $('#form_data_service_type'),
    dataIndex: 'service_type',
    useSelect2: true,
  })
})
