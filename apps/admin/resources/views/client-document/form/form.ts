import '~resources/views/main'

$(() => {
  const toggleDescription = () => {
    const $descriptionField = $('#form_data_description').parent().parent()

    const OTHER_DOCUMENT_TYPE = 10
    const selectedType = $('#form_data_type').val()
    // @todo получать ID (Другие документы) с бекенда
    if (Number(selectedType) === OTHER_DOCUMENT_TYPE) {
      $descriptionField.show()
    } else {
      $descriptionField.hide()
    }
  }

  $('#form_data_type')
    .ready(toggleDescription)
    .change(toggleDescription)
})
