import { ShowDialogResponse, ToggleCloseFunction, ToggleLoadingFunction } from '~lib/confirm-dialog'

export interface ShowNotConfirmedReasonDialogResponse extends ShowDialogResponse {
  reason: string
}

export const showNotConfirmedReasonDialog = (): Promise<ShowNotConfirmedReasonDialogResponse> => new Promise((resolve): void => {
  let reasonType: string | null = null
  let reasonDescription: string

  const $descriptionElement = $('<textarea />', {
    id: 'form_data_description',
    class: 'form-control',
  }).on('input', (e) => {
    reasonDescription = $(e.target).val() as string
  })
  const $reasonDescriptionField = $('<div />', { class: 'row form-field field-textarea field-description' })
    .append('<label for="form_data_description" class="col-sm-5 col-form-label">Описание</label>')
    .append(
      $('<div />', { class: 'col-sm-7 d-flex align-items-center' }).append($descriptionElement),
    )

  const $selectElement = $('<div class="col-sm-7 d-flex align-items-center"><select name="data[reason]" id="form_data_reason" class="form-select form-control" required><option value=""></option><option value="1">Нет мест</option><option value="2">Двойное бронирование</option><option value="3">Другое</option></select></div>')
    .change((e) => {
      const $element = $(e.target)
      reasonType = $element.val() as string
      if (reasonType !== '3') {
        $reasonDescriptionField.hide()
        reasonDescription = $element.find('option:selected').text()
      } else {
        $reasonDescriptionField.show()
      }
    })

  const $reasonSelectField = $('<div />', {
    class: 'row form-field field-enum field-type field-required',
  })
    .append('<label for="form_data_reason" class="col-sm-5 col-form-label">Выберите причину</label>')
    .append($selectElement)

  const $form = $('<form />', {
    method: 'post',
  })
    .append($reasonSelectField)
    .append($reasonDescriptionField)

  window.WindowDialog({
    title: 'Укажите причину',
    html: $form,
    buttons: [{ text: 'Сохранить', cls: 'btn btn-primary', handler: 'submit' }, 'cancel'],
    beforeSubmit: (form: any, closeHandler: ToggleCloseFunction, toggleLoading: ToggleLoadingFunction) => {
      resolve({ result: true, reason: reasonDescription, toggleLoading, toggleClose: closeHandler })
      return false
    },
    close: () => {
      resolve({ result: false, reason: reasonDescription, toggleLoading: () => {}, toggleClose: () => {} })
    },
  })
})
