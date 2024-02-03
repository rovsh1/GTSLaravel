export const useDeleteWithConfirm = (deleteUrl: string, message?: string) => {
  const $form = $(`<form method="post" action="${deleteUrl}">`
    + `<p>${message || 'Удалить запись?'}</p>`
    + '<input type="hidden" name="_method" value="delete"/>'
    + '</form>')

  window.WindowDialog({
    title: 'Подтверждение',
    html: $form,
    buttons: [{
      text: 'Подтвердить',
      cls: 'btn btn-danger',
      handler: 'submit',
    }, 'cancel'],
  })
}
