export type ToggleLoadingFunction = (value?: boolean) => void

export type ToggleCloseFunction = () => void

export type ShowDialogReturn = Promise<{ result: boolean; toggleLoading: ToggleLoadingFunction; toggleClose:ToggleCloseFunction }>

export const showConfirmDialog = (message?: string): ShowDialogReturn => new Promise((resolve): void => {
  const preparedMessage = message || 'Удалить запись?'
  const $form = $(`<form method="post"><p>${preparedMessage}</p></form>`)

  window.WindowDialog({
    title: 'Подтверждение',
    html: $form,
    buttons: [{ text: 'Подтвердить', cls: 'btn btn-danger', handler: 'submit' }, 'cancel'],
    beforeSubmit: (form: any, closeHandler: ToggleCloseFunction, toggleLoading: ToggleLoadingFunction) => {
      resolve({ result: true, toggleLoading, toggleClose: closeHandler })
      return false
    },
    close: () => {
      resolve({ result: false, toggleLoading: () => {}, toggleClose: () => {} })
    },
  })
})
