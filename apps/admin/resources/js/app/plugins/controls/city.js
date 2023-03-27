$.fn.elementCity = function (options) {
    return $(this).childCombo(Object.assign({
        url: '/admin/v1/reference/city/search',
        value: +get_url_parameter('city_id'),
        disabledText: 'Выберите страну',
        parent: options.countrySelector,
        dataIndex: 'country_id'
    }, options));
};
