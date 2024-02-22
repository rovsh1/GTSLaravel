import '~resources/views/main'

$(() => {
  const constantName = $('.content-header div.title').text()

  if (constantName === 'BasicCalculatedValue') {
    $('label[for="form_data_value"]').text('Значение (UZS)')
  }
})
