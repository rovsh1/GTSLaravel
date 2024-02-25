import { useSelectElement } from 'gts-common/widgets/select-element/select-element'

import '~resources/views/main'

$(async () => {
  const $hotelSelect = (await useSelectElement(document.querySelector<HTMLSelectElement>('#form_data_hotel_id')))?.select2Instance

  $('#form_data_room_id').childCombo({
    urlGetter: (hotelId: string) => `/hotels/${hotelId}/rooms/list`,
    disabledText: 'Выберите отель',
    parent: $hotelSelect,
    dataIndex: 'hotel_id',
  })
})
