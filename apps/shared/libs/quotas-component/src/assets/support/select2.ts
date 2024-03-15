import 'select2'
import '~assets/vendors/select2'

$.fn.select2.defaults.set('theme', 'bootstrap4')
$.fn.select2.defaults.set('language', {
  noResults: () => 'Не найдено результатов',
})

$(document).on('select2:open', () => {
  document.querySelector<HTMLInputElement>('.select2-search__field')?.focus()
})
