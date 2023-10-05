import '~resources/views/main'

$(() => {
  const $hotelSelect = $('#form_data_hotel_id').select2()

  $('#form_data_hotel_room_id').childCombo({
    urlGetter: (hotelId: string) => `/hotels/${hotelId}/rooms/list`,
    disabledText: 'Выберите отель',
    parent: $hotelSelect,
    dataIndex: 'hotel_id',
  })
})
