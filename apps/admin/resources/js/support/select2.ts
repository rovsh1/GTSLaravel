// eslint-disable-next-line import/no-extraneous-dependencies
import 'select2'
import '~resources/js/vendor/select2'

$.fn.select2.defaults.set('theme', 'bootstrap4')
$.fn.select2.defaults.set('language', {
  noResults: () => 'Не найдено результатов',
})

$(document).on('select2:open', () => {
  document.querySelector<HTMLInputElement>('.select2-search__field')?.focus()
})
