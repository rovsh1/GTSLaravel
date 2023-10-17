import '~resources/views/main'

$(() => {
  const supplierId = $('#form_data_supplier_id').val()

  // @todo подгружать разные поля в зависимости от услуги, например airport_id если работаем с трансфером в аэропорт

  console.log(supplierId)
})
