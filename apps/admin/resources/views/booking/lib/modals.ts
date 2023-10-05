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

  const $form = $('<form />', { method: 'post' })
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
      // @todo click outside не вызывает событие close, из-за этого интерфейс зависает

      resolve({ result: false, reason: reasonDescription, toggleLoading: () => {}, toggleClose: () => {} })
    },
  })
})

export interface ShowCancelFeeDialogResponse extends ShowDialogResponse {
  clientCancelFeeAmount: number
  cancelFeeAmount: number
}

export const showCancelFeeDialog = (withClientCancelFee?: boolean): Promise<ShowCancelFeeDialogResponse> => new Promise((resolve): void => {
  let clientCancelFeeAmount: number = 0
  let cancelFeeAmount: number = 0
  let $clientReasonDescriptionField = null
  const $form = $('<form />', { method: 'post' })

  const $descriptionElement = $('<input />', {
    id: 'form_data_penalty_net',
    class: 'form-control',
    type: 'number',
    required: true,
  }).on('input', (e) => {
    cancelFeeAmount = Number($(e.target).val())
  })
  const $reasonDescriptionField = $('<div />', { class: 'row form-field field-text field-value field-required' })
    .append(`<label for="form_data_penalty_net" class="${withClientCancelFee ? 'col-sm-12' : 'col-sm-5'} col-form-label">Сумма штрафа</label>`)
    .append(
      $('<div />', { class: `${withClientCancelFee ? 'col-sm-12' : 'col-sm-7'} d-flex align-items-center` }).append($descriptionElement),
    )

  if (withClientCancelFee) {
    const $clientDescriptionElement = $('<input />', {
      id: 'form_data_penalty_gross',
      class: 'form-control',
      type: 'number',
      required: true,
    }).on('input', (e) => {
      clientCancelFeeAmount = Number($(e.target).val())
    })
    $clientReasonDescriptionField = $('<div />', { class: 'row form-field field-text field-value field-required' })
      .append(`<label for="form_data_penalty_gross" class="${withClientCancelFee ? 'col-sm-12' : 'col-sm-5'} col-form-label">Сумма штрафа для клиента</label>`)
      .append(
        $('<div />', { class: `${withClientCancelFee ? 'col-sm-12' : 'col-sm-7'} d-flex align-items-center` }).append($clientDescriptionElement),
      )
    $form.append($clientReasonDescriptionField)
    $form.append($reasonDescriptionField)
  } else {
    $form.append($reasonDescriptionField)
  }

  window.WindowDialog({
    title: 'Укажите сумму штрафа',
    html: $form,
    buttons: [{ text: 'Сохранить', cls: 'btn btn-primary', handler: 'submit' }, 'cancel'],
    beforeSubmit: (form: any, closeHandler: ToggleCloseFunction, toggleLoading: ToggleLoadingFunction) => {
      resolve({ result: true, clientCancelFeeAmount, cancelFeeAmount, toggleLoading, toggleClose: closeHandler })
      return false
    },
    close: () => {
      resolve({ result: false, clientCancelFeeAmount, cancelFeeAmount, toggleLoading: () => {}, toggleClose: () => {} })
    },
  })
})
