import { WindowDialog } from '~widgets/dialog/helpers'

export const useDeleteWithConfirm = (deleteUrl: string, message?: string) => {
  const $form = $(`<form method="post" action="${deleteUrl}">`
    + `<p>${message || 'Удалить запись?'}</p>`
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
  })
}
