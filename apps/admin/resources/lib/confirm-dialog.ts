export type ToggleLoadingFunction = (value?: boolean) => void

export type ToggleCloseFunction = () => void

export interface ShowDialogResponse {
  result: boolean
  toggleLoading: ToggleLoadingFunction
  toggleClose:ToggleCloseFunction
}

export const showConfirmDialog = (message: string, buttonClass: string = 'btn-primary'): Promise<ShowDialogResponse> => new Promise((resolve): void => {
  const $form = $(`<form method="post"><p>${message}</p></form>`)

  window.WindowDialog({
    title: 'Подтверждение',
    html: $form,
    buttons: [{ text: 'Подтвердить', cls: `btn ${buttonClass}`, handler: 'submit' }, 'cancel'],
    beforeSubmit: (form: any, closeHandler: ToggleCloseFunction, toggleLoading: ToggleLoadingFunction) => {
      resolve({ result: true, toggleLoading, toggleClose: closeHandler })
      return false
    },
    close: () => {
      resolve({ result: false, toggleLoading: () => {}, toggleClose: () => {} })
    },
  })
})
