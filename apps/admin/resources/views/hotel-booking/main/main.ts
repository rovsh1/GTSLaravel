import '~resources/views/main'

$(() => {
  // $('#form_data_city_id').elementCity({ countrySelector: '#form_data_country_id' })

  $('#form_data_hotel_id').childCombo({
    url: '/hotels/search',
    disabledText: 'Выберите город',
    parent: $('#form_data_city_id'),
    dataIndex: 'city_id',
  })

  $('#form_data_hotel_room_id').childCombo({
    urlGetter: (hotelId: number) => `/hotels/${hotelId}/rooms/list`,
    disabledText: 'Выберите отель',
    parent: $('#form_data_hotel_id'),
    dataIndex: 'hotel_id',
  })
})
