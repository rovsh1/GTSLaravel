import { useSelectElement } from 'gts-common/widgets/select-element'

import '~resources/views/main'

$(async () => {
  const $hotelSelect = (await useSelectElement(document.querySelector<HTMLSelectElement>('#form_data_hotel_id')))?.select2Instance
  const $dataTypeSelect = $('#form_data_type')

  $('#form_data_room_id').childCombo({
    urlGetter: (hotelId: string) => `/hotels/${hotelId}/rooms/list`,
    disabledText: 'Выберите отель',
    parent: $hotelSelect,
    dataIndex: 'hotel_id',
  })

  const setFieldValueText = () => {
    if ($dataTypeSelect?.val() === '2') {
      $('.field-value label').text('Значение (в сумах)')
    } else {
      $('.field-value label').text('Значение')
    }
  }

  $dataTypeSelect?.change(() => {
    setFieldValueText()
  })

  setFieldValueText()
})
