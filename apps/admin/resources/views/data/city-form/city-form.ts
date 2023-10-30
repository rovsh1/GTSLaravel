import '~resources/views/main'
import '~resources/js/app/plugins/controls/coordinates-input'

$(() => {
  $('#form_data_coordinates').coordinatesInput({
    addressInput: '#form_data_name_ru',
  })
})
