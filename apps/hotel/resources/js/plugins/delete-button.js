$.fn.deleteButton = function () {
  return $(this).click(function (e) {
    const url = $(this).data('url')
    if (!url) {
      return
    }

    e.preventDefault()

    const $btn = $(this)
    const $form = $(`<form method="post" action="${url}">`
			+ '<p>Удалить запись?</p>'
			+ '<input type="hidden" name="_method" value="delete"/>'
			+ '</form>')

    WindowDialog({
      title: 'Подтверждение',
      html: $form,
      buttons: [{
        text: 'Подтвердить',
        cls: 'btn btn-danger',
        handler: 'submit',
      }, 'cancel'],
      submit: () => {
        $btn.attr('disabled', true)
      },
    })
  })
}
