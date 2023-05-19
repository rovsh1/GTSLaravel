import '~resources/views/main'

$(() => {
  $('#form_data_hotel_id').childCombo({
    url: '/hotels/search',
    value: window.get_url_parameter('city_id'),
    disabledText: 'Выберите город',
    parent: $('#form_data_city_id'),
    dataIndex: 'city_id',
  })
})
