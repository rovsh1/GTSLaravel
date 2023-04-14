import '../main'
import '../../app/plugins/controls/coordinates-input'

$(() => {
  $('#form_data_coordinates').coordinatesInput({
    addressInput: '#form_data_name_ru',
  })
})
