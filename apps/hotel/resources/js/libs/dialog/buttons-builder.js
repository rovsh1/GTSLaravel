import Button from './button'

export const defaultHandlers = {
  ok() { this.close() },
  close() { this.close() },
  cancel() { this.close() },
  submit() { this.form.submit() },
}

const defaultButtons = {
  cancel: () => ({
    text: WindowDefaults.buttonCancel || 'Отмена',
    cls: 'btn btn-cancel',
    handler: 'close',
  }),
  close: () => ({
    text: WindowDefaults.buttonClose || 'Закрыть', cls: 'btn btn-cancel', handler: 'close',
  }),
  submit: () => ({
    text: WindowDefaults.buttonSubmit || 'Сохранить',
    cls: 'btn btn-primary',
    handler: 'submit',
  }),
  ok: () => ({
    text: 'ОК',
    cls: 'btn btn-primary',
    handler: 'ok',
  }),
}

export function buttonsRenderer(win, buttons, buttonsEl) {
  let button
  const l = buttons.length
  for (let i = 0; i < l; i++) {
    button = buttons[i]
    if (typeof button === 'string') {
      button = defaultButtons[button]()
    }

    if (typeof button.handler === 'string') {
      if (defaultHandlers[button.handler]) {
        button.handler = defaultHandlers[button.handler]
      } else {
        button.handler = window[button.handler]
      }
    }

    button.scope = win
    if (!(button instanceof Button)) {
      button = new Button(button)
    }

    button.render(buttonsEl)
  }
}
