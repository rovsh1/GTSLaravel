import '~resources/views/main'

$(() => {
  $('#form_data_city_id').elementCity({
    countrySelector: '#form_data_country_id',
    allowEmpty: true,
  })

  $('#form_data_hotel_id').childCombo({
    url: '/hotels/search',
    disabledText: 'Выберите город',
    parent: $('#form_data_city_id'),
    dataIndex: 'city_id',
    allowEmpty: true,
  })
})
