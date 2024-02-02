import { useSelectElement } from '~widgets/select-element/select-element'

import '~resources/views/main'
import '~resources/js/plugins/coordinates-input'

$(() => {
  const element = document.querySelector<HTMLSelectElement>('#form_data_supplier_id')
  useSelectElement(element)
  $('#form_data_coordinates').coordinatesInput({
    addressInput: '#form_data_address',
  })
})
