$.fn.elementCity = function (options) {
  return $(this).childCombo({
    url: '/cities/search',
    disabledText: 'Выберите страну',
    parent: options.countrySelector,
    dataIndex: 'country_id',
    ...options,
  })
}
